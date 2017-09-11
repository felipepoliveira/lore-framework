<?php

require_once __DIR__ . "/../../models/Usuario.php";

/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 05/09/2017
 * Time: 14:12
 */
class UsuarioController extends \lore\mvc\ApiController
{
    public function createNewModelInstance()
    {
        return new Usuario();
    }

    /**
     * @uri /gerarToken
     */
    public function login(){

        $this->loadModel();
        $this->getModel()->gerarToken();
        $this->send([
            "token" => $this->getModel()->getToken()
        ]);
    }
}