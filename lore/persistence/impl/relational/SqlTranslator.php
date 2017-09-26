<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 18/09/2017
 * Time: 21:09
 */

namespace lore\persistence;


abstract class SqlTranslator
{
    /**
     * @var RelationalRepository
     */
    protected $repository;

    /**
     * ISqlTranslator constructor.
     * @param RelationalRepository $repository
     */
    function __construct(RelationalRepository $repository){
        $this->repository = $repository;
    }

    /**
     * @return RelationalRepository
     */
    public function getRepository(): RelationalRepository
    {
        return $this->repository;
    }

    /**
     * Delete the entity from the repository
     * @param Entity $entity
     * @return string
     */
    public abstract function delete($entity) : string;

    /**
     * @param Entity $entity
     * @return string
     */
    public abstract function insert($entity) : string;

    /**
     * Create the query Sql
     * @param Query $query
     * @return mixed
     */
    public abstract function query(Query $query) : string;

    /**
     * Create an UPDATE sql script
     * @param Entity $entity
     * @return string
     */
    public abstract function update($entity) : string;
}