<?php

/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 02/02/2018
 * Time: 14:05
 */
class DocumentRepository extends \lore\persistence\Repository
{

    /**
     * Load the repository data with the data set by the user in the persistence configuration file.
     * @param $data - The specific configuration of the repository
     * @return void
     */
    public function loadData($data)
    {
        // TODO: Implement loadData() method.
    }

    /**
     * Delete an entity from the repository
     * @param $entity \lore\persistence\Entity|\lore\persistence\Entity
     * @return int - The number of affected rows
     * @throws \lore\persistence\PersistenceException
     */
    public function delete($entity): int
    {
        // TODO: Implement delete() method.
    }

    /**
     * Return an flag indicating if the given $entity already exists in the repository
     * @param $entity \lore\persistence\Entity|\lore\persistence\Entity
     * @return bool
     */
    public function exists($entity): bool
    {
        // TODO: Implement exists() method.
    }

    /**
     * Insert an entity(s) into repository
     * @param $entity \lore\persistence\Entity|\lore\persistence\Entity
     * @return
     * @throws \lore\persistence\PersistenceException - If an errors occurs in the repository while inserting the new entity
     */
    public function insert($entity)
    {
        // TODO: Implement insert() method.
    }

    /**
     * Create an query syntax object with the methods to build queries
     * @param $class \stdClass|null
     * @see Query
     * @return \lore\persistence\Query
     */
    public function query($class = null): \lore\persistence\Query
    {
        // TODO: Implement query() method.
    }

    /**
     * Update an existing entity into repository
     * @param $entity \lore\persistence\Entity|\lore\persistence\Entity
     * @return int - Number of affected registers
     */
    public function update($entity): int
    {
        // TODO: Implement update() method.
    }
}