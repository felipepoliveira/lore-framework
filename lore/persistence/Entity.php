<?php
namespace lore\persistence;

require_once "EntityMetadata.php";

use lore\Lore;

trait Entity
{
    /**
     * @var EntityMetadata
     */
    private static $entitiesMetadata = [];

    /**
     * @var string
     */
    private $entityName;

    public function metadata() : EntityMetadata{
        //Get the metadata singleton
        $class = get_class($this);
        $metadata = self::$entitiesMetadata[$class] ?? false;
        if(!$metadata){
            $metadata = new EntityMetadata($this);
            self::$entitiesMetadata[$class] = $metadata;
        }

        //return it
        return $metadata;
    }


    /**
     * Delete the current entity from the repository. It can throws an PersistenceException
     * if the deletion of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function delete(){
        Lore::app()->getPersistence()->getRepository($this->metadata()->getRepositoryName())->delete($this);
    }

    /**
     * Insert the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function insert(){
        Lore::app()->getPersistence()->getRepository($this->metadata()->getRepositoryName())->insert($this);
    }

    /**
     * Save (update or insert) the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function save(){
        Lore::app()->getPersistence()->getRepository($this->metadata()->getRepositoryName())->save($this);
    }

    /**
     * Update the current entity state into the repository. It can throws an PersistenceException
     * if the state of this object affect in the business rule of the repository, or
     * if an technical errors occurs while executing the persistence
     */
    public function update(){
        Lore::app()->getPersistence()->getRepository($this->metadata()->getRepositoryName())->update($this);
    }
}