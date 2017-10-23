<?php
namespace lore\persistence;

use lore\util\DocCommentUtil;

require_once "SqlTranslator.php";


class GenericSqlTranslator extends SqlTranslator
{

    public  function delete($entity)
    {
        throw new PersistenceException("Not implemented yet!");
    }

    public function insert($entity) : TransactionTranslateResult
    {
        $metadata = $entity->metadata();


        //Get the transaction fields to make the insert script
        $transactionFields = $this->transactionalFields($metadata, $entity);

        //Generate the SQL
        $sql =  "INSERT INTO " . $this->entityFullName($metadata) . " " . $this->fieldsInParenthesis($transactionFields) .
            " VALUES " . $this->valuesInParenthesis($transactionFields);

        $insertTranslationResult = new TransactionTranslateResult($entity, $sql);

        //Get the composed fields
        foreach ($metadata->getEntityFields() as $composedField){
            //Get the composed entity
            $composedEntity = $metadata->getPropertyValue($composedField->getPropertyName(), $entity);

            //If the entity is not persisted already it ignore the new insert
            if(!$composedEntity || $this->isAlreadyPersisted($composedEntity->metadata(), $composedEntity)){
                continue;
            }

            $insertTranslationResult->setPreviousInsertion($this->insert($composedEntity));
        }

        return $insertTranslationResult;
    }

    public function query(Query $query)
    {
        $queryFields = $this->queryFields($query->getMetadata());


        $sql = "SELECT\n" . $this->fieldsInList($query, $queryFields) . "\nFROM\n\t" .
            $this->entityNameWithAlias($query->getMetadata()) . " " .
            $this->joinTables($query, $query->getMetadata()) . " " .
            $this->filters($query, $queryFields);

        echo "<pre>";
        die($sql);
        return $sql;
    }

    public  function update($entity)
    {
        $metadata = $entity->metadata();

        //If the entity does not have any identification fields it can't update
        if (count($metadata->getIdentificationFields()) == 0) {
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
     * Return an flag indicating if the given $entity is already persisted. The flag returns true when
     * the identification field of the $entity has an value
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
     * Return the SQL syntax to make the JOIN in the queried tables. If the $query::$fetchMode is Query::FETCH_ALL
     * this method will join ALL the composed entities of the Queried entity. If the $query::$fetchMode is Query::FETCH_ONLY
     * or FETCH_EXCEPT it will only JOIN if the requested field is of a composed entity
     * @param Query $query
     * @param EntityMetadata $metadata
     * @return string
     */
    protected function joinTables(Query $query, EntityMetadata $metadata, $aliasPrefix = "", $propertyPrefix = ""){
        $return = "";
        $entityName = $aliasPrefix .  $metadata->getEntityName();

        //Iterate over all entities inside the entity metadata
        foreach ($metadata->getEntityFields() as $entityField){
            $composedEntityMetadata = Entity::metadataOf($entityField->getType());
            $rightJoinProperty = $propertyPrefix . $composedEntityMetadata->getEntityName(); //address
            $rightJoinTableAlias = $aliasPrefix . $metadata->getEntityName() . "." . $composedEntityMetadata->getEntityName();

            //If the join is not needed, skip to another entity field
            if(!$this->isJoinNeeded($query, $rightJoinProperty)){
                continue;
            }

            $return .= "\nJOIN\n\t" . $composedEntityMetadata->getEntityName() . " AS `$rightJoinTableAlias`\n" .
                "ON\n\t`$entityName`." . $entityField->getName() . " = `$rightJoinTableAlias`." .
                $this->uniqueIdentificationField($composedEntityMetadata)->getName() ;

            $return .= $this->joinTables($query, $composedEntityMetadata, "$entityName.", $composedEntityMetadata->getEntityName() . ".");
        }

        return $return;
    }



    /**
     * Create the QueryField objects of the fields that can be used in the query build
     * @param EntityMetadata $metadata
     * @param string $fieldNamePrefix
     * @return QueryField[]
     */
    protected function queryFields(EntityMetadata $metadata, $fieldNamePrefix = "", $entityNamePrefix = ""){
        $queryFields = [];

        foreach ($metadata->getFields() as $field){
            if($field->isEntity()){

                //Get the metadata of the entity by the Field::type (read in @var annotation)
                $composedEntityMetadata = Entity::metadataOf($field->getType());
                $queryFields = array_merge($queryFields, $this->queryFields(
                    $composedEntityMetadata,
                    $fieldNamePrefix . $composedEntityMetadata->getEntityName() . ".",
                    $entityNamePrefix . $metadata->getEntityName() . "."
                ));

            }else{
                $queryFields[] = new QueryField($fieldNamePrefix . $field->getName(), $entityNamePrefix . $metadata->getEntityName(), $field);
            }
        }

        return $queryFields;
    }

    /**
     * Return the TransactionField fields of the entity. The transacional fields is the fields that will be inserted
     * or updated in the database. The fields marked as '@'auto is skipped.
     * @param EntityMetadata $metadata
     * @param Entity $entity
     * @see TransactionField
     * @return TransactionField[]
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

                //If the entity is currently null skip to another object
                if($composedEntity === null ) continue;

                //Check if the composed entity use Entity trait
                if(method_exists($composedEntity, 'metadata')){

                    $composedEntityMetadata = $composedEntity->metadata();

                    foreach ($composedEntityMetadata->getIdentificationFields() as $identificationField){

                        $propValue =
                            $composedEntityMetadata->getPropertyValue($identificationField->getPropertyName(),
                                $composedEntity)
                            ?? "@auto";

                        $transactionalFields[] = new TransactionField($field, $propValue);
                    }
                }else{
                    //
                    throw new PersistenceException("The entity " . get_class($entity) . "::" . $field->getPropertyName() .
                    " must implement the lore\\persistence\\Entity trait to make an transaction");
                }
            }else{
                $transactionalFields[] = new TransactionField($field, $metadata->getPropertyValue($field->getPropertyName(), $entity));
            }

        }

        return $transactionalFields;
    }

    /**
     * Get the identification field of the entity
     * @param EntityMetadata $metadata
     * @return Field
     */
    protected  function uniqueIdentificationField(EntityMetadata $metadata){
        foreach ($metadata->getIdentificationFields() as $identificationField){
            return $identificationField;
        }

        throw new PersistenceException("The entity " . $metadata->getEntityClassName() . " does not have an identification field");
    }

    /**
     * Translate the query filters (WHERE)
     * @param Query $query
     * @param QueryField[] $queryFields
     * @return string
     */
    protected function filters(Query $query, $queryFields)  {
        //If the query does not have a filter return an empty string
        if(!$query->hasFilter()) return "";

        //Get the first filter of the query
        $filter = $query->getFilter();
        $return = "\nWHERE";

        //Iterate over all filters of the filter queue
        do{
            //Get the queryfield by the queryField
            $queryField = $this->queryFieldByName($queryFields, $filter->getField());


            if(!$queryField){
                throw new PersistenceException("The filter list of the query  has an invalid filter name: "
                    . $filter->getField());
            }

            $return .= "\n\t`" .
                $queryField->getEntityName() . "`.`" . $queryField->getField()->getName() . "` " .  //entity.field
                $this->filterType($filter) . " " .                                              // =
                $this->value($filter->getValue(), $filter) . "\n" .                              //  'value'
                $this->bindType($filter) . "\t";                                                       //'AND', 'OR' or ''

        }while($filter->hasNextFilter() && ($filter = $filter->getNextFilter()));

        return $return;
    }

    /**
     * Return an QueryField by its name
     * @param QueryField[] $queryFields
     * @param string $name
     * @return QueryField|false
     */
    protected function queryFieldByName($queryFields, string $name){
        foreach ($queryFields as $queryField){
            if($queryField->getName() === $name){
                return $queryField;
            }
        }

        return false;
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
     * @param Query $query
     * @param QueryField[] $queryFields
     * @return string
     */
    protected function fieldsInList(Query $query, $queryFields){
        $return = "";
        $lastIndex = $this->getLastIndexInQuery($query, $queryFields);
        $counter = 0;

        foreach ($queryFields as $queryField){

            if($this->isFieldNotRequested($query, $queryField->getName())){
                continue;
            }

            $counter++;
            $return .= "\t`" . $queryField->getEntityName() ."`" . "." . $this->fieldName($queryField->getField()) .
            " AS `" . $queryField->getName() . "`";

            if($counter <= $lastIndex){
                $return .= ",\n";
            }
        }

        return $return;
    }

    /**
     * @param Query $query
     * @param string $fieldName
     * @return bool
     */
    protected function isFieldNotRequested(Query $query, string $fieldName){

        //If the query is in FETCH_ONLY skip to the next field if the current field is not in the fetch list
        return ($query->getFetchMode() === Query::FETCH_ONLY &&
            !in_array($fieldName, $query->getFetchFields()))

            || //or

        //The opposite of the verification above
        ($query->getFetchMode() === Query::FETCH_EXCEPT &&
            in_array($fieldName, $query->getFetchFields()));
    }

    protected function isJoinNeeded(Query $query, string $joinTablePrefix){

        //if the query is FETCH_ALL the join is always needed
        $result = false;
        if($query->getFetchMode() === Query::FETCH_ALL){
            return true;
        }else if($query->getFetchMode() === Query::FETCH_ONLY){
            foreach ($query->getFetchFields() as $fetchField){
                if(strpos($fetchField, $joinTablePrefix) === 0){
                    return true;
                }
            }
        }else{
            $result = true;
            foreach ($query->getFetchFields() as $fetchField){
                if(strpos($fetchField, $joinTablePrefix) === 0) {
                    $result = false;
                }
            }
        }

        //Check if a filter request the field to be joined
        foreach ($query->getFilters() as $filter){
            if(strpos($filter->getField(), $joinTablePrefix) === 0){
                return true;
            }
        }

        return $result;
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

    /**
     * @param Query $query
     * @param QueryField[] $queryFields
     * @return int
     */
    protected function getLastIndexInQuery(Query $query, $queryFields) : int {
        switch ($query->getFetchMode()){
            case Query::FETCH_ALL:
                $lastIndex = count($queryFields) - 1;
                break;
            case Query::FETCH_ONLY:
                $lastIndex = count($query->getFetchFields()) - 1;
                break;
            case Query::FETCH_EXCEPT:
                $lastIndex = count($queryFields) - count($query->getFetchFields()) - 1;
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
     * Return the formatted entity full name repository.entity with alias
     * @param $metadata EntityMetadata
     * @return  string
     */
    protected function entityNameWithAlias(EntityMetadata $metadata){
        return $metadata->getEntityName() . " AS `" . $metadata->getEntityName() . "`";
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