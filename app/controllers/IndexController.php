<?php

use lore\Lore;
use lore\mvc\Controller;

class IndexController extends Controller
{
    /**
     * @uri /
     */
    public function index(){
        $this->render("index.php");
    }
}