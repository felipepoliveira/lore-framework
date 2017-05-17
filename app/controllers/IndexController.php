<?php
use lore\mvc\Controller;
use lore\web\Session;

require_once __DIR__ .  "/../models/User.php";

class IndexController extends Controller
{
    /**
     * @var User
     */
    private $model;

    /**
     * @uri /
     * @method get
     */
    public function openHome(){
        $this->render("index.php");
    }
}