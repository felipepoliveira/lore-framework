<?php

use lore\mvc\ViewController;

class IndexController extends ViewController
{
    public function createNewModelInstance()
    {
        return null;
    }

    /**
     * Render the index.php view
     * @uri /
     * @method get
     */
    public function index(){
        $this->render("index.php");
    }
}