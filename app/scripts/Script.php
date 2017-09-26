<?php
require_once __DIR__ . "/../../lore/mvc/Model.php";
require_once __DIR__ . "/../models/Usuario.php";

$u = new Usuario();
$u->setEmail("duarte@email.com");
$u->setSenha("senha123");
//$u->save();
//die;



$queryUsuario = \lore\Lore::app()->getPersistence()->getRepository("lore/mysql")->query(Usuario::class);

$usuarios = $queryUsuario->where("email")->startsWith("d")->and("id")->greaterOrEqualsThan(1)->all();

echo "<pre>";
die(var_dump($usuarios));


