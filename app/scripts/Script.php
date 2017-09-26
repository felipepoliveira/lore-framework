<?php
require_once __DIR__ . "/../../lore/mvc/Model.php";
require_once __DIR__ . "/../models/Usuario.php";

use lore\persistence\Query;

$u = new Usuario();
$u->setEmail("duarte@email.com");
$u->setSenha("senha123");
//$u->save();
//die;



$queryUsuario = \lore\Lore::app()->getPersistence()->getRepository("lore/mysql")->query(Usuario::class);
$queryUsuario->where("id")->equals(2)->one()->delete();


