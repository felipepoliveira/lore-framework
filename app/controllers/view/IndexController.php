<?php

use lore\mvc\ViewController;

require_once __DIR__ . "/../../models/Usuario.php";
require_once __DIR__ . "/../../models/Ocorrencia.php";

class IndexController extends ViewController
{
    public function createNewModelInstance()
    {
        return null;
    }

    /**
     * @uri /
     * @method get
     */
    public function index(){
        $ocorrencia = new Ocorrencia();
        $ocorrencia->setId(32);
        $ocorrencia->setNome('Olá Felipe');
        $this->render("index.lore.php",[
            'teste'=>'oi',
            'other'=>'olá mundo',
            'usuario' => new Usuario(),
            'ocorrencias' => [$ocorrencia,$ocorrencia,$ocorrencia,$ocorrencia]
        ]);

    }

    /**
     * @uri /register
     * @method get
     */
    public function register(){
        $this->render("user/form.php");
    }
}