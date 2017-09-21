<?php
namespace lore\persistence;

use lore\util\DocCommentUtil;

require_once "Field.php";


class EntityMetadata
{
    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var string
     */
    private $repositoryName;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * Load the entity metadata based on a entity representative
     * @param $entity Entity - The entity representative
     */
    function __construct($entity)
    {
        //Create the reflection class used internally
        $this->reflectionClass = new \ReflectionClass(get_class($entity));

        $this->loadRepositoryData();
        $this->loadEntityData();
    }

    private function loadEntityData(){
        $this->loadEntityName();
        $this->loadFields();
    }

    private function loadEntityName(){
        $this->entityName = DocCommentUtil::readAnnotationValue($this->reflectionClass->getDocComment(), "entity") ?? get_class($this);
    }

    private function loadFields(){
        foreach ($this->reflectionClass->getProperties() as $property){
            //Check if the property has the field annotation...
            if(($fieldAnnot = $this->isFieldAnnotated($property)) !== false){
                $field = new Field();

                //Set the field name
                $field->setName($this->formatFieldName($fieldAnnot, $property->getName()));
                $field->setPropertyName($property->getName());
                $field->setIdentifier($this->isIdentifierAnnotated($property));
                $field->setAuto($this->isAutoAnnotated($property));

                //Put the field in field list
                $this->fields[] = $field;
            }
        }
    }

    /**
     * Check if the property has the @'auto' annotation.
     * @param $refProp \ReflectionProperty
     * @return bool
     */
    private function isAutoAnnotated($refProp) : bool{
        return DocCommentUtil::annotationExists($refProp->getDocComment(), "auto");
    }

    /**
     * Check if the property has the @'id' annotation.
     * @param $refProp \ReflectionProperty
     * @return bool
     */
    private function isIdentifierAnnotated($refProp) : bool{
        return DocCommentUtil::readAnnotationValue($refProp->getDocComment(), "id") !== false;
    }

    /**
     * Check if the property has the @'field' annotation. Return the value if it has
     * @param $refProp \ReflectionProperty
     * @return string|false
     */
    private function isFieldAnnotated($refProp){
        return DocCommentUtil::readAnnotationValue($refProp->getDocComment(), "field");;
    }

    private function formatFieldName($annotationName, $defaultName){
        if(strlen($annotationName) > 0){
            return $annotationName;
        }else{
            return $defaultName;
        }
    }

    /**
     * Get the value of an $entity property invoking its get method
     * @param $prop string
     * @param $entity Entity
     * @return mixed
     */
    public function getPropertyValue($prop, $entity){

        try{

            $method = $this->reflectionClass->getMethod('get' . ucfirst($prop));
            return $method->invoke($entity);

        }catch (\Exception $e){
            throw new PersistenceException("The entity " . $this->getEntityName() . " is trying to access an 
            property that does not have its get method. Create the get" . ucfirst($prop) , " method in the " .
        get_class($entity) . " to access it");
        }
    }

    /**
     * Load the repository data to be used in persistence methods
     */
    private function loadRepositoryData(){
        //Create an reflection class of the user of this trait and get the @repository annotation in class
        $this->repositoryName = DocCommentUtil::readAnnotationValue($this->reflectionClass->getDocComment(), "repository");

        //Store the repository name
        if(!$this->repositoryName){
            $this->repositoryName = null;
        }
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }
}