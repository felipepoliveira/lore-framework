<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 14/10/2017
 * Time: 09:30
 */

namespace lore\persistence;


class TransactionTranslateResult
{
    /**
     * @var TransactionTranslateResult
     */
    private $previousInsertion;

    /**
     * @var TransactionTranslateResult
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
     * @return TransactionTranslateResult
     */
    public function getPreviousInsertion()
    {
        return $this->previousInsertion;
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
     * @param TransactionTranslateResult $previousInsertion
     */
    public function setPreviousInsertion(TransactionTranslateResult $previousInsertion)
    {
        $this->previousInsertion = $previousInsertion;
        $this->previousInsertion->nextInsertion = $this;
    }

    /**
     * @return TransactionTranslateResult
     */
    public function getNextInsertion()
    {
        return $this->nextInsertion;
    }

    /**
     * @param TransactionTranslateResult $nextInsertion
     */
    public function setNextInsertion(TransactionTranslateResult $nextInsertion)
    {
        $this->nextInsertion = $nextInsertion;
        $this->nextInsertion->previousInsertion = $this;
    }

    public function first(){
        $current = $this;

        while(($aux = $current->getPreviousInsertion()) !== null){
            $current = $aux;
        }

        return $current;
    }

    /**
     * @param string $sql
     */
    public function setSql(string $sql)
    {
        $this->sql = $sql;
    }
}