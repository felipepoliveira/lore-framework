<?php
require_once __DIR__ . "/../../lore/mvc/Model.php";
require_once __DIR__ . "/../models/Usuario.php";

$u = new Usuario();

$u->setSenha("123");
$u->setEmail("usuario@email.com");
$u->save();

$usuarioRepository = new \lore\persistence\RelationalRepository();

$usuarioRepository->query()->where("id")->equalTo("1");