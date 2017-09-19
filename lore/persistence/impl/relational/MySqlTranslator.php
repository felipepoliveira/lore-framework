<?php
namespace lore\persistence;

use lore\util\DocCommentUtil;

require_once "ISqlTranslator.php";


class MySqlTranslator implements ISqlTranslator
{


    public function insert($entity): string
    {
        $reflectionClass = new \ReflectionClass(get_class($entity));
        $className =    ($annot = (DocCommentUtil::readAnnotationValue($reflectionClass->getDocComment(), "entity")))?
                        $annot : strtolower($reflectionClass->getName());

        $fields = $this->reflectionFields($reflectionClass, $entity);

        if(count($fields) == 0){
            throw new PersistenceException("The entity has no fields to persist");
        }

        $sql = "INSERT INTO `$className`" . $this->fieldsInParenthesis($fields) . " VALUES " . $this->valuesInParenthesis($fields);


        die($sql);

        return "";
    }

    /**
     * @param $fields array
     * @return string
     */
    protected function fieldsInParenthesis($fields){
        $lastIndex = count($fields) - 1;
        $i = 0;
        $return = "(";

        foreach ($fields as $name => $value){
            $return .= "`" . $name . "`";
            if($i < $lastIndex){
                $return .= ",";
            }
            $i++;
        }

        $return .= ")";

        return $return;
    }

    /**
     * @param $reflectionClass \ReflectionClass
     * @return array
     */
    protected function reflectionFields($reflectionClass, $entity){
        $fields = [];
        foreach ($reflectionClass->getProperties() as $property){

            $property->setAccessible(true);
            if(DocCommentUtil::annotationExists($property->getDocComment(), "field")){
                $fieldName =    ($annot = (DocCommentUtil::readAnnotationValue($property->getDocComment(), "field")))?
                                $annot : strtolower($property->getName());

                $value =  $property->getValue($entity);

                if($this->hasValue($value)){
                    $fields[$fieldName] = $value;
                }
            }

        }

        return $fields;
    }

    /**
     * @param $fields array
     * @return string
     */
    protected function valuesInParenthesis($fields){
        $lastIndex = count($fields) - 1;
        $i = 0;
        $return = "(";

        foreach ($fields as $name => $value){
            $return .= ":$name";
            if($i < $lastIndex){
                $return .= ",";
            }
            $i++;
        }

        $return .= ")";

        return $return;
    }

    protected function formatValue($value){

    }

    /**
     * @param $value
     * @return  bool
     */
    protected function hasValue($value){
        return $value !== false;
    }

}