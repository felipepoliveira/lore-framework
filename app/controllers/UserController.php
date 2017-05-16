<?php
use lore\Lore;
use lore\mvc\Controller;
use lore\web\Session;

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

    function __construct(MvcRouter $mvcRouter)
    {
        parent::__construct($mvcRouter);
        $this->dao = new UserDAO();
        $this->model = new User();
    }

    /**
     * @uri /signup
     * @method get
     */
    public function openForm(){
        $this->render("user/form.php");
    }

    /**
     * @uri /login
     * @method get
     */
    public function openLogin(){
        $this->render("user/login.php");
    }

    /**
     * @uri /changePassword
     * @method post
     */
    public function changePassword(){

    }

    /**
     * @uri /save
     * @method post
     */
    public function save(){
        //If model loading and validation is OK...
        if($this->load($this->model, true)){

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