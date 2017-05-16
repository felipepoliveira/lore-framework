<?php
abstract class DAO
{
    /**
     * @var ConnectionFactory
     */
    private $connectionFactory;

    function __construct()
    {
        $this->connectionFactory = new ConnectionFactory();
    }

    /**
     * Return the PDO object from connection factory
     * @return PDO
     */
    public function getPdo(){
        return $this->connectionFactory->getPdo();
    }

    /**
     * Create an PDOStatement from the PDO inside the ConnectionFactory
     * @param $sql
     * @return PDOStatement
     */
    public function preapre($sql){
        return $this->connectionFactory->getPdo()->prepare($sql);
    }

}