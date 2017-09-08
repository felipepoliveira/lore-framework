<?php
class DAO
{
    /**
     * @var PDO
     */
    protected $pdo;

    function __construct()
{
$hostname = "localhost";
$database = "lore";
$user = "root";
$password = "root";
$rdm = "mysql";

$this->pdo = new PDO("$rdm:host=$hostname;dbname=$database", $user, $password);
$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
}
