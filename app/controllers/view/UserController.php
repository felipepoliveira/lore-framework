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

    protected function validateEmailExists(){
        $email = $this->getModel()->getEmail();
        if($this->queryUser->where("email")->equals($email)->count() > 0){
            $this->getResponse()->putErrorsOn(
                "model.email", [
                    "emailExists" => Lore::app()->getStringProvider()->getString(
                        "User.email.emailExists",
                        "The email is already in use")
                ]
            );

            return false;
        }else{
            return true;
        }
    }

    /**
     * @uri /register
     * @method post
     */
    public function register(){
        if($this->loadAndValidateModel() && $this->validateEmailExists()){
            $this->getModel()->hashPassword();
            $this->repository->insert($this->getModel());
            $this->redirect("");
        }else{
            $this->putModelInResponse();
            $this->render("user/form.php");
        }
    }

}