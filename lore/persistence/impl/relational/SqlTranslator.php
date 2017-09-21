<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 18/09/2017
 * Time: 21:09
 */

namespace lore\persistence;


abstract class ISqlTranslator
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
     * @param $entity Entity
     * @return string
     */
    public abstract function insert($entity) : string;
}