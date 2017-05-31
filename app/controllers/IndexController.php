<?php
use lore\mvc\Controller;

class IndexController extends Controller
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

}