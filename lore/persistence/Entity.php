<?php
namespace lore\persistence;

require_once "EntityMetadata.php";

use lore\Lore;

/**
 * Represents an class that will be used to persist data into a repository.
 * Any class that implements this trait will have the persistence methods embedded. This methods manipulate the
 * object that calls it into the repository.
 *
 * The class that implements this trait must:
 * -configure the '@'repository that it will be persisted
 * -configure the name of the '@'entity. The name of the entity identifies the class into the repository, for example:
 * The class Product is configured into a database in the table tb_product, so the name of the entity is
 * '@'entity tb_product
 * -Define the fields that will be persisted into the repository
 * -Define (if requested by the repository implementation) the id of an field
 *
 * Example:
 *
 * '@'repository sales/mysql
 * '@'entity tb_product
 * class Product{
 *      use lore\persistence\Entity;
 *
 *      '@'id
 *      '@'field
 *      private $id;
 *
 *      '@'field
 *      private $name;
 *
 *      '@'price
 *      private $price;
 * }
 *
 * @package lore\persistence
 */
trait Entity
{
    /**
     * @var EntityMetadata[]
     */
    private static $entitiesMetadata = [];

    /**
     * @var string
     */
    private $entityName;

    public static function metadataOf($entityClass){

        $metadata = self::$entitiesMetadata[$entityClass] ?? false;

        if(!$metadata){
            $refClass = new \ReflectionClass($entityClass);
            $entity = $refClass->newInstance();
            $metadata = new EntityMetadata($entity);
            self::$entitiesMetadata[$entityClass] = $metadata;
        }

        return $metadata;
    }

    public static function containsMetadata($entityClass){
        return isset(self::$entitiesMetadata[$entityClass]);
    }

    /**
     * The metadata of the entity. It returns an singleton that represents the data about this entity
     * This singleton is created when the first object of the entity is instantiated. This singleton is stored
     * in an static array Entity::entitiesMetadata where the $key is the name of the entity and the value is the
     * instance of the EntityMetadata
     * @see EntityMetadata
     * @return EntityMetadata
     */
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

    /**
     * @return EntityMetadata[]
     */
    public static function getEntitiesMetadata(): array
    {
        return self::$entitiesMetadata;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}