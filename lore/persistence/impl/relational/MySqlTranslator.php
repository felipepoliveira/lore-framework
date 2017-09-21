<?php
namespace lore\persistence;

use lore\util\DocCommentUtil;

require_once "SqlTranslator.php";


class MySqlTranslator extends SqlTranslator
{


    public function insert($entity): \PDOStatement
    {
        $metadata = $entity->metadata();

        //Translate the fields
        $sql = "INSERT INTO " . $this->entityFullName($metadata) . " " . $this->fieldsInParenthesis($metadata) .
        " VALUES " . $this->fieldsInParenthesis($metadata, ":");
        //End the sql command
        $sql .= ";";

        //Create the stmt
        $stmt = $this->repository->getPdo()->prepare($sql);


        //Bind the values
        foreach ($metadata->getFields() as $field) {
            if($field->isAuto()) continue;

            $propVal = $metadata->getPropertyValue($field->getPropertyName(), $entity);
            $stmt->bindValue($field->getName(), $propVal);
        }

        return $stmt;
    }

    /**
     * @param $metadata EntityMetadata
     * @return string
     */
    protected function fieldsInParenthesis($metadata, $prefix = "", $ignoreAuto = true){
        $return = "(";
        $lastIndex = count($metadata->getFields()) - 1;
        $counter = 0;

        foreach ($metadata->getFields() as $field){

            $counter++;

            //Ig ignore automatic valued fields is true skip to the next field if the current is automatic
            if($ignoreAuto && $field->isAuto()){
                continue;
            }

            $return .= $prefix . $field->getName();

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
    protected function entityFullName(EntityMetadata $metadata){
        return "`" . $this->repository->getDatabase() . "`.`" . $metadata->getEntityName() . "`";
    }



}