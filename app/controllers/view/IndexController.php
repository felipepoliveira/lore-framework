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
        $this->render("index.php");
    }

    /**
     * @uri /register
     * @method get
     */
    public function register(){
        $this->render("user/form.php");
    }
}