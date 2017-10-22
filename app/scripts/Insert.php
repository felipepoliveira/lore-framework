<?php
require_once __DIR__ . "/../models/User.php";

$address = new Address();
$user = new User();
$userQuery = \lore\Lore::app()->getPersistence()->getRepository("lore/mysql")->query(User::class);

//$address->setId(1);
$address->setPublicPlace("My street, 1");

$user->setEmail("email@email.com");
$user->setAddress($address);
$user->setName("John Mayer");
$user->setPassword("password123");

$user->insert();

