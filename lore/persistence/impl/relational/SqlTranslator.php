<?php
namespace lore\persistence;

require_once "TransactionTranslateResult.php";

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
    public abstract function delete($entity);

    /**
     * @param Entity $entity
     * @return string
     */
    public abstract function insert($entity) : TransactionTranslateResult;

    /**
     * Create the query Sql
     * @param Query $query
     * @return mixed
     */
    public abstract function query(Query $query);

    /**
     * Create an UPDATE sql script
     * @param Entity $entity
     * @return string
     */
    public abstract function update($entity);
}