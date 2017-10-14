<?php
namespace lore\persistence;

use lore\util\DocCommentUtil;

require_once "SqlTranslator.php";


class MySqlTranslator extends SqlTranslator
{

    public  function delete($entity)
    {
        $metadata = $entity->metadata();

        //Translate the fields
        $sql = "DELETE FROM " . $this->entityFullName($metadata) . " " .
            " WHERE " . $this->identifierFieldsInQuery($metadata, $entity);

        return $sql;
    }

    public function insert($entity) : InsertTranslationResult
    {
        $return = false;

        $metadata = $entity->metadata();

        //Get the composed fields
        foreach ($metadata->getEntityFields() as $composedField){
            //Get the composed entity
            $composedEntity = $metadata->getPropertyValue($composedField->getPropertyName(), $entity);

            //If the entity is not persisted already it ignore the new insert
            if(!$composedEntity || $this->isAlreadyPersisted($composedEntity->metadata(), $composedEntity)){
                continue;
            }

            //Otherwise... Call this method recursively for the composed entity
            $return = $this->insert($composedEntity);
        }

        //Get the transaction fields to make the insert script
        $transactionFields = $this->transactionalFields($metadata, $entity);
        $sql =  "INSERT INTO " . $this->entityFullName($metadata) . " " . $this->fieldsInParenthesis($transactionFields) .
            " VALUES " . $this->valuesInParenthesis($transactionFields);

        //If the result is not set already (does not have any composed entities) create an new instance of the
        //object, otherwise add the next insertion result into the queue
        if(!$return){
            $return = new InsertTranslationResult($entity, $sql);
        }else{
            $return->setNextInsertion(new InsertTranslationResult($entity, $sql));
        }

        return $return;
    }

    public function query(Query $query)
    {
        $sql = "SELECT " . $this->fieldsInList($query) . " FROM " . $this->entityFullName($query->getMetadata()) .
            $this->filters($query);

        return $sql;
    }

    public  function update($entity)
    {
        $metadata = $entity->metadata();

        //If the entity does not have any identification fields it can't update
        if(count($metadata->getIdentificationFields()) == 0){
            throw new PersistenceException("The " . $metadata->getEntityClassName() . " does not have any 
            identification field. Use the @id annotation in the identification property of the Entity to 
            determine the identification field");
        }

        //Create the UPDATE script
        $sql = "UPDATE " . $this->entityFullName($metadata) . " SET " . $this->fieldsSettingValues($metadata, $entity) .
        " WHERE " . $this->identifierFieldsInQuery($metadata, $entity);

        return $sql;
    }

    /**
     * @param EntityMetadata $metadata
     * @param Entity $entity
     * @return bool
     */
    protected function isAlreadyPersisted($metadata, $entity){

        $isAlreadyPersisted  = true;

        foreach ($metadata->getIdentificationFields() as $identificationField){
            if(! ($metadata->getPropertyValue($identificationField->getPropertyName(), $entity))){
                $isAlreadyPersisted = false;
                break;
            }
        }

        return $isAlreadyPersisted;

    }

    /**
     * @param EntityMetadata $metadata
     * @param Entity $entity
     * @return array
     */
    protected function transactionalFields($metadata, $entity){
        $transactionalFields = [];
        foreach ($metadata->getFields() as $field){

            //*Auto fields is not considered transactional
            if($field->isAuto()){
                continue;
            }

            //If the entity is field is a composed entity, it get its id
            if($field->isEntity()){
                $composedEntity = $metadata->getPropertyValue($field->getPropertyName(), $entity);

                //Check if the composed entity use Entity trait
                if($composedEntity && method_exists($composedEntity, 'metadata')){

                    $composedEntityMetadata = $composedEntity->metadata();

                    foreach ($composedEntityMetadata->getIdentificationFields() as $identificationField){

                        $propValue =
                            $composedEntityMetadata->getPropertyValue($identificationField->getPropertyName(), $composedEntity) ?? "@auto";

                        $transactionalFields[] = new TransactionField($field,
                            $propValue);
                    }

                }else{
                    throw new PersistenceException("The entity " . get_class($entity) . "::" . $field->getName() .
                    " must implement the lore\\persistence\\Entity trait to make an transaction");
                }

            }else{
                $transactionalFields[] = new TransactionField($field, $metadata->getPropertyValue($field->getPropertyName(), $entity));
            }

        }

        return $transactionalFields;
    }

    /**
     * Translate the query filters (WHERE)
     * @param Query $query
     * @return string
     */
    protected function filters(Query $query)  {
        //If the query does not have a filter return an empty string
        if(!$query->hasFilter()) return "";

        //Get the first filter of the query
        $filter = $query->getFilter();
        $return = "WHERE";

        //Iterate over all filters of the filter queue
        do{
            $return .= " " .
                $this->entityName($query->getMetadata()) . ".`" . $filter->getField() . "` " .  //entity.field
                $this->filterType($filter) . " " .                                              // =
                $this->value($filter->getValue(), $filter) . " " .                              //  'value'
                $this->bindType($filter);                                                       //'AND', 'OR' or ''

        }while($filter->hasNextFilter() && ($filter = $filter->getNextFilter()));

        return $return;
    }

    /**
     * Translate the bind type of the filter:
     * BIND_OR turns in "OR"
     * BIND_AND turns in "AND"
     * @param $filter
     * @return string
     */
    protected function bindType($filter){
        switch ($filter->getBindType()){
            case QueryFilter::BIND_OR:
                return "OR";
            case QueryFilter::BIND_AND:
                return "AND";
            case QueryFilter::BIND_XOR:
                return "XOR";
            default:
                return "";
        }
    }


    /**
     * Translate the type of the filter
     * FILTER_DIFFERENT turns in "<>"
     * FILTER_EQUALS turns in "="
     * @param QueryFilter $filter
     * @return string
     */
    protected function filterType($filter){
        switch ($filter->getFilterType()){
            case QueryFilter::FILTER_CONTAINS:
            case QueryFilter::FILTER_ENDS_WITH:
            case QueryFilter::FILTER_STARTS_WITH:
                return "LIKE";
            case QueryFilter::FILTER_DIFFERENT:
                return "<>";
            case QueryFilter::FILTER_EQUALS:
                return "=";
            case QueryFilter::FILTER_LESS_OR_EQUALS_THAN:
                return "<=";
            case QueryFilter::FILTER_LESS_THAN:
                return "<";
            case QueryFilter::FILTER_GREATER_OR_EQUALS_THAN:
                return ">=";
            case QueryFilter::FILTER_GREATER_THAN:
                return ">";
            default:
                throw new PersistenceException("The filter type used in the query is not supported");
        }
    }

    /**
     * Translate the fields of the entity
     * @param Query $query
     * @return string
     */
    protected function fieldsInList(Query $query){
        $return = "";
        $lastIndex = $this->getLastIndexInQuery($query);
        $counter = 0;

        foreach ($query->getMetadata()->getFields() as $field){

            //If the query is in FETCH_ONLY skip to the next field if the current field is not in the fetch list
            if($query->getFetchMode() === Query::FETCH_ONLY &&
            !in_array($field->getPropertyName(), $query->getFetchFields())) continue;

            //The opposite of the verification above
            if($query->getFetchMode() === Query::FETCH_EXCEPT &&
                in_array($field->getPropertyName(), $query->getFetchFields())) continue;

            $counter++;

            $return .= $this->entityName($query->getMetadata()) . "." . $this->fieldName($field);

            if($counter <= $lastIndex){
                $return .= ", ";
            }
        }

        return $return;
    }

    /**
     * Translate the fields of the entity
     * @param EntityMetadata $metadata
     * @param Entity $entity
     * @return string
     */
    protected function fieldsSettingValues(EntityMetadata $metadata,$entity){
        $return = "";
        $lastIndex = count($metadata->getFields()) - 1;
        $counter = 0;

        foreach ($metadata->getFields() as $field){
            $counter++;

            $return .= $this->entityName($metadata) . "." . $this->fieldName($field);
            $return .= " = " .  $this->value($metadata->getPropertyValue($field->getPropertyName(), $entity));

            if($counter <= $lastIndex){
                $return .= ", ";
            }
        }

        return $return;
    }

    /**
     * @param EntityMetadata $metadata
     * @param Entity $entity
     * @return string
     */
    protected function identifierFieldsInQuery(EntityMetadata $metadata, $entity){
        $return = "";
        $lastIndex = count($metadata->getIdentificationFields()) - 1;
        $counter = 0;

        foreach ($metadata->getIdentificationFields() as $field){
            $counter++;

            $return .= $this->entityName($metadata) . "." . $this->fieldName($field);
            $return .= " = " .  $this->value($metadata->getPropertyValue($field->getPropertyName(), $entity));

            if($counter <= $lastIndex){
                $return .= "AND ";
            }
        }

        return $return;
    }

    /**
     * @param TransactionField[] $transactionFields
     * @return string
     */
    protected function fieldsInParenthesis($transactionFields){
        $return = "(";
        $lastIndex = count($transactionFields) - 1;
        $counter = 0;

        foreach ($transactionFields as $field){

            $counter++;

            $return .= $field->getField()->getName();

            if($counter <= $lastIndex){
                $return .= ", ";
            }
        }

        return $return . ")";
    }

    protected function getLastIndexInQuery(Query $query) : int {
        switch ($query->getFetchMode()){
            case Query::FETCH_ALL:
                $lastIndex = count($query->getMetadata()->getFields()) - 1;
                break;
            case Query::FETCH_ONLY:
                $lastIndex = count($query->getFetchFields()) - 1;
                break;
            case Query::FETCH_EXCEPT:
                $lastIndex = count($query->getMetadata()->getFields()) - count($query->getFetchFields()) - 1;
                break;
        }

        if($lastIndex === false){
            throw new PersistenceException("The query fetch mode \"" . $query->getFetchMode() . "\" was not 
            recognized or not implemented by the repository");
        }

        if($lastIndex < 0){
            throw new PersistenceException("The number of fields requested can not be negative");
        }

        return $lastIndex;
    }

    /**
     * @param mixed $value
     * @param QueryFilter $filter
     * @return mixed
     */
    protected function value($value, $filter = null){

        if(isset($filter)){
            //Check if the filter is a LIKE operator
            switch ($filter->getFilterType()){
                case QueryFilter::FILTER_STARTS_WITH:
                    $value = $value . "%";
                    break;
                case QueryFilter::FILTER_ENDS_WITH:
                    $value = "%" . $value;
                    break;
                case QueryFilter::FILTER_CONTAINS:
                    $value = "%" . $value . "%";
                    break;
            }
        }


        if($value === null){
            $value = 'NULL';
        }
        else if(is_string($value)){
            $value = $this->repository->getPdo()->quote($value, \PDO::PARAM_STR);
        }

        return $value;
    }

    /**
     * @param TransactionField[] $transactionFields
     * @return string
     */
    public function valuesInParenthesis($transactionFields){
        $return = "(";
        $lastIndex = count($transactionFields) - 1;
        $counter = 0;

        foreach ($transactionFields as $field){
            $counter++;

            $return .= $this->value($field->getValue());

            if($counter <= $lastIndex){
                $return .= ", ";
            }
        }

        return $return . ")";
    }

    /**
     * Return the formatted entity full name repository.entity
     * @param $metadata EntityMetadata
     * @return  string
     */
    protected function entityName(EntityMetadata $metadata){
        return "`" . $metadata->getEntityName() . "`";
    }

    /**
     * Return the formatted entity full name repository.entity
     * @param $metadata EntityMetadata
     * @return  string
     */
    protected function entityFullName(EntityMetadata $metadata){
        return "`" . $this->repository->getDatabase() . "`.`" . $metadata->getEntityName() . "`";
    }

    /**
     * Return the formatted entity name of the field
     * @param Field $field
     * @return string
     */
    protected function fieldName(Field $field){
        return "`" . $field->getName() . "`";
    }



}