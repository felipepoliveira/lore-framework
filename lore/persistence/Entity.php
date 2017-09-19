<?php
namespace lore\persistence;

require_once "Field.php";

use lore\Lore;
use lore\util\DocCommentUtil;

trait Entity
{
    /**
     * @var string
     */
    private $repositoryName;

    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    function __construct()
    {
        //Create the reflection class used internally
        $this->reflectionClass = new \ReflectionClass(get_class($this));

        $this->loadRepositoryData();
        $this->loadEntityData();
    }

    private function loadEntityData(){
        $this->loadFields();
    }

    private function loadFields(){
        foreach ($this->reflectionClass->getProperties() as $property){
            //Check if the property has the field annotation...
            if(($fieldAnnot = $this->isFieldAnnotated($property)) !== false){
                $field = new Field();

                //Set the field name
                $field->setName($this->formatFieldName($fieldAnnot, $property->getName()));
                $field->setIdentifier($this->isIdentifierAnnotated($property));

                //Put the field in field list
                $this->fields[] = $field;
            }
        }
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
     * @param $refProp \ReflectionProperty
     * @return  mixed
     */
    private function getPropertyValue($refProp){

        $getMethodName = 'get' . ucfirst($refProp->getName());

        try{
            $method = $this->reflectionClass->getMethod($getMethodName);
            return $method->invoke($this);
        }catch (\Exception $e){
            throw new PersistenceException("The property " . $refProp->getName() . " is marked as
            @field and does not have a get method to had this value read. Create the $getMethodName method");
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
     * @return mixed
     */
    public abstract function getIdentifier();

    /**
     * Delete the current entity from the repository. It can throws an PersistenceException
     * if the deletion of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function delete(){
        Lore::app()->getPersistence()->getRepository($this->repositoryName)->delete($this);
    }

    /**
     * Insert the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function insert(){
        Lore::app()->getPersistence()->getRepository($this->repositoryName)->insert($this);
    }

    /**
     * Save (update or insert) the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function save(){
        Lore::app()->getPersistence()->getRepository($this->repositoryName)->save($this);
    }

    /**
     * Update the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function update(){
        Lore::app()->getPersistence()->getRepository($this->repositoryName)->update($this);
    }
}