<?php

require_once __DIR__ . "/../../models/User.php";

use lore\Lore;
use lore\mvc\ViewController;
use lore\persistence\Query;

class UserController extends ViewController
{
    /**
     * @var \lore\persistence\Repository
     */
    private $repository;

    /**
     * @var Query
     */
    private $queryUser;

    public function createNewModelInstance()
    {
        $this->repository = Lore::app()->getPersistence()->getRepository("lore/mysql");
        $this->queryUser = $this->repository->query(User::class);

        return new User();
    }

    //MVC

    /**
     * @uri /form
     */
    public function form(){
        $this->render("user/form.php");
    }

    /**
     * @uri /register
     * @method post
     */
    public function register(){
        if($this->loadAndValidateModel()){
            $this->repository->insert($this->getModel());
            $this->redirect("");
        }else{
            $this->putModelInResponse();
            $this->render("user/form.php");
        }
    }

}