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
     * @var Field[]
     */
    private $identificationFields = [];

    /**
     * @var Field[]
     */
    private $entityFields = [];

    /**
     * @var string
     */
    private $repositoryName;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $entityClassName;

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
        $this->entityClassName = get_class($entity);
        $this->reflectionClass = new \ReflectionClass($this->entityClassName);

        $this->loadRepositoryData();
        $this->loadEntityData();
    }

    /**
     * Load data of the entity:
     * The entity name
     * The entity fields
     */
    private function loadEntityData(){
        $this->loadEntityName();
        $this->loadFields();
    }

    /**
     * Load the name of the entity from '@'entity annotation in the class. If the entity name in annotation is
     * empty, the name of the entity will be the name of the class
     */
    private function loadEntityName(){
        $entityName = DocCommentUtil::readAnnotationValue($this->reflectionClass->getDocComment(), "entity");
        if(strlen($entityName) == 0){
            $entityName = strtolower($this->reflectionClass->getName());
        }

        $this->entityName = $entityName;
    }

    /**
     * * Fields
     *  -Name of the field;
     *  -Name of the property
     *  -The field type
     */
    private function loadFields(){
        foreach ($this->reflectionClass->getProperties() as $property){
            //Check if the property has the field annotation...
            if(($fieldAnnot = $this->isFieldAnnotated($property)) !== false){
                $field = new Field();

                //Set the field name
                $field->setName($this->formatFieldName($fieldAnnot, $property->getName()));
                $field->setPropertyName($property->getName());
                $field->setAuto($this->isAutoAnnotated($property));
                $field->setType($this->readType($property));

                //Check if the field has a identifier and add the field in the identification fields
                $field->setIdentifier($this->isIdentifierAnnotated($property));
                if($field->isIdentifier()){
                    $this->identificationFields[] = $field;
                }

                //Check if is a composition
                $field->setIsEntity($this->isEntity($property));
                if($field->isEntity()){
                    $field->setCompositionType($this->readCompositionType($property));
                    $this->entityFields[] = $field;
                }

                //Put the field in field list
                $this->fields[$field->getName()] = $field;
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
     * @param \ReflectionProperty $refProp - The property
     * @return bool
     */
    private function isEntity($refProp) : bool {
        return  DocCommentUtil::annotationExists($refProp->getDocComment(), "one") ||
                DocCommentUtil::annotationExists($refProp->getDocComment(), "many");
    }

    /**
     * @param \ReflectionProperty $refProp
     * @return int
     */
    private function readCompositionType($refProp) : int{
        if(DocCommentUtil::annotationExists($refProp->getDocComment(), "one")){
            return Field::COMPOSITION_ONE;
        }else if(DocCommentUtil::annotationExists($refProp->getDocComment(), "many")){
            return Field::COMPOSITION_MANY;
        }else{
            throw new PersistenceException("The composition type ['one', 'many'] was not defined in property " .
                $this->entityName . "::" . $refProp->getName());
        }
    }

    /**
     * @param \ReflectionProperty $refProp
     * @return int
     */
    private function readType($refProp){
        return DocCommentUtil::readAnnotationValue($refProp->getDocComment(), "var");
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
        return DocCommentUtil::readAnnotationValue($refProp->getDocComment(), "field");
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
     * Set the value of an $entity property invoking its set method
     * @param $prop string
     * @param $value mixed
     * @param $entity Entity
     */
    public function setPropertyValue($prop, $value, $entity){
        try{

            $method = $this->reflectionClass->getMethod('set' . ucfirst($prop));
            $method->invoke($entity, $value);

        }catch (\Exception $e){
            throw new PersistenceException("The entity " . $this->getEntityName() . " is trying to write in an 
            property that does not have its set method. Create the set" . ucfirst($prop) , " method in the " .
                get_class($entity) . " to change it");
        }
    }

    /**
     * Find an specific field by the given $fieldName
     * @param $fieldName
     * @return bool|Field
     */
    public function findFieldByName($fieldName){
        return $this->fields[$fieldName] ?? false;
    }

    /**
     * Find an specific field by the given $fieldName
     * @param $propName
     * @return bool|Field
     */
    public function findFieldByPropertyName($propName){
        foreach ($this->fields as $field) {
            if($field->getPropertyName() === $propName){
                return $field;
            }
        }

        return false;
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
     * @return Field[]
     */
    public function getIdentificationFields()
    {
        return $this->identificationFields;
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

    /**
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Return all composed fields of the entity
     * @return Field[]
     */
    public function getEntityFields(): array
    {
        return $this->entityFields;
    }
}