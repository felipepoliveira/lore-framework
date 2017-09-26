<?php

use lore\mvc\ViewController;

class IndexController extends ViewController
{
    public function createNewModelInstance()
    {}

    /**
     * @uri /
     * @method get
     */
    public function index(){
        $this->render("index.php");
    }

}