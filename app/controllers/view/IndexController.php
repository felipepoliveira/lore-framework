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
     */
    public function index(){
        $this->render("index.php");
    }

    /**
     * @uri /register
     */
    public function userRegister(){
        $this->render("user/form.php");
    }

}