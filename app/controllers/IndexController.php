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
        $_GET = [
            "user.name" => "Felipe",
            "user.lastName" => "Pereira de Oliveira",
            "user.email" => ["a", "b"]
        ];
        $model = new User();
        $model->load(\lore\Lore::app()->getRequest());
    }
}