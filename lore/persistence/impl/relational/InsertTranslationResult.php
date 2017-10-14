<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 14/10/2017
 * Time: 09:30
 */

namespace lore\persistence;


class InsertTranslationResult
{
    /**
     * @var InsertTranslationResult
     */
    private $nextInsertion;

    /**
     * @var string'
     */
    private $sql;

    /**
     * @var Entity
     */
    private $entity;

    function __construct($entity = null, string $sql = null)
    {
        $this->entity = $entity;
        $this->sql = $sql;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return InsertTranslationResult
     */
    public function getNextInsertion()
    {
        return $this->nextInsertion;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param InsertTranslationResult $nextInsertion
     */
    public function setNextInsertion(InsertTranslationResult $nextInsertion)
    {
        $this->nextInsertion = $nextInsertion;
    }

    /**
     * @param string $sql
     */
    public function setSql(string $sql)
    {
        $this->sql = $sql;
    }
}