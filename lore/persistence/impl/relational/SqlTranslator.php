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
     * @param Entity $entity
     * @return \PDOStatement
     */
    public abstract function insert($entity) : string;

    /**
     * Create the query Sql
     * @param Query $query
     * @return mixed
     */
    public abstract function query(Query $query) : string;
}