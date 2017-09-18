<?php
namespace lore\persistence;

require_once __DIR__ . "/../Repository.php";

class RelationalRepository extends Repository
{
    public function exists(Entity $entity): bool
    {
        // TODO: Implement exists() method.
    }

    public function insert(Entity $entity)
    {
        // TODO: Implement insert() method.
    }

    public function queryByIdentifier($identifier)
    {
        // TODO: Implement queryByIdentifier() method.
    }

    public function query(): QuerySyntax
    {
        // TODO: Implement query() method.
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

}