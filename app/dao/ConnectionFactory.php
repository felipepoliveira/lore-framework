<?php
class ConnectionFactory
{
    /**
     * @var PDO
     */
    private $pdo;

    function __construct()
    {
        $server = "localhost";
        $database = "task";
        $port = 3306;
        $user = "root";
        $password = "root";

        $this->pdo = new PDO("host=$server;database=$database;port=$port;", $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Return the generated pdo
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}