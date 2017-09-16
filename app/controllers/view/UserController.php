<?php
use lore\mvc\ViewController;

require_once __DIR__ . "/../../models/Usuario.php";
require_once __DIR__ . "/../../dao/UserDAO.php";

/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 12/08/2017
 * Time: 10:36
 */
class UserController extends ViewController
{
    /**
     * @var UserDAO
     */
    private $dao;

    public function createNewModelInstance()
    {
        return new Usuario();
    }

    /**
     * @uri /form1
     * @method get
     */
    public function form1(){
        $this->render("user/form1.php");
    }

    /**
     * @uri /form2
     * @method get
     */
    public function form2(){
        $this->render("user/form2.php");
    }

    /**
     * @uri /save
     */
    public function save(){
        if($this->loadAndValidateModel()){
            $this->redirect("/lore");
        }else{
            $this->putErrorsInResponse();
            $this->putModelInResponse();
            $this->render("user/form1.php");
        }
    }
}