<?php
use lore\Lore;
use lore\mvc\Controller;
use lore\web\Session;
require_once  __DIR__ . "/../models/User.php";

class UserController extends Controller
{
    /**
     * @var UserDAO
     */
    private $dao;

    /**
     * @var User
     */
    private $model;

    function __construct($mvcRouter)
    {
        parent::__construct($mvcRouter);
        $this->model = new User();
    }

    /**
     * @uri /signin
     * @method get
     */
    public function openLogin(){
        $this->render("user/login.php", [$this->model]);
    }

    /**
     * @uri /signup
     * @method get
     */
    public function openForm(){
        $this->render("user/form.php", [$this->model]);
    }

    /**
     * @uri /changePassword
     * @method post
     */
    public function changePassword(){

    }

    /**
     * @uri /authenticate
     * @method post
     */
    public function authenticate(){
        if($this->load($this->model)){
            $this->redirect("/");
        }else{
            $this->render("user/login.php");
        }
    }

    /**
     * @uri /save
     * @method post
     */
    public function save(){
        //If model loading and validation is OK...
        if($this->load($this->model, \lore\mvc\ValidationModes::ALL)){

            //Get the authenticated user id
            $userId = Session::get("user.id");

            //Check if is update or insert
            if($userId !== null){
                $this->model->setId($userId);
                $this->dao->update($this->model);
            }else{
                $this->dao->insert($this->model);
            }

            //Redirect to home
            $this->redirect("/");
        }else{
            $this->render("user/form.php", [
               "user" => $this->model
            ]);
        }
    }
}