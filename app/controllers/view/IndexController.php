<?php
use lore\mvc\ViewController;

class IndexController extends ViewController
{
    public function createNewModelInstance()
    {

    }

    /**
     * @uri /
     */
    public function index(){
        $this->render("index.php");
    }

    /**
     * @uri /a
     */
    public function teste(){
        $this->render("index.php");
    }

}