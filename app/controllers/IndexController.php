<?php
use lore\mvc\Controller;

class IndexController extends Controller
{
    /**
     * @method [get, post]
     * @uri /usuario/$id
     */
    public function index(){
        $this->render("index.php");
    }
}