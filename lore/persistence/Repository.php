<?php
namespace lore\persistence;

require_once "Entity.php";
require_once "PersistenceException.php";

abstract class Repository
{
    /**
     * Return an flag indicating if the given $entity already exists in the repository
     * @param $entity Entity
     * @return bool
     */
    public abstract function exists(Entity $entity) : bool ;

    /**
     * Insert an entity into repository
     * @param $entity Entity
     * @return
     * @throws PersistenceException - If an errors occurs in the repository while inserting the new entity
     */
    public abstract function insert(Entity $entity);

    /**
     * Return an Entity by the $identifier. Return false if the entity does no exsts
     * @param $identifier
     * @return Entity|false
     */
    public abstract function queryByIdentifier($identifier);

    /**
     * Create an query syntax object with the methods to build queries
     * @see QuerySyntax
     * @return QuerySyntax
     */
    public abstract function query() : QuerySyntax;

    /**
     * Update an existing entity into repository
     * @param $entity Entity
     */
    public abstract function update($entity);

    /**
     * Save the entity in the repository. If the entity already exists it makes an Repository::update, otherwise
     * it Repository::insert the $entity
     * @param Entity $entity
     * @return mixed
     */
    public function save(Entity $entity){
        if(self::exists($entity)){
            self::update($entity);
        }else{
            self::insert($entity);
        }
    }
}