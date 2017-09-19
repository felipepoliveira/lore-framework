<?php
namespace lore\persistence;

use lore\Lore;
use lore\util\DocCommentUtil;

trait Entity
{
    /**
     * @var string
     */
    private $repositoryName;

    function __construct()
    {
        //Create an reflection class of the user of this trait and get the @repository annotation in class
        $reflectionClass = new \ReflectionClass(get_class($this));
        $this->repositoryName = DocCommentUtil::readAnnotationValue($reflectionClass->getDocComment(), "repository");

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