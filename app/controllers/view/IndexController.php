<?php

use lore\mvc\ViewController;

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
        $this->render("index.php",[
            'teste'=>'oi',
            'other'=>'olá mundo'
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