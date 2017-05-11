<?php
use lore\mvc\Controller;

class IndexController extends Controller
{
    /**
     * @method get
     * @uri /
     */
    public function index(){
        $this->redirect("index/oi");
    }

    /**
     * @method get
     * @uri /oi
     */
    public function oi(){
        $this->render("index.php");
    }

}