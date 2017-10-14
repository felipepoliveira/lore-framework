<?php
require_once __DIR__ . "/../models/User.php";

$address = new Address();
$user = new User();
$userQuery = \lore\Lore::app()->getPersistence()->getRepository("lore/mysql")->query(User::class);

echo "<pre>";
die(var_dump($userQuery->all()));

