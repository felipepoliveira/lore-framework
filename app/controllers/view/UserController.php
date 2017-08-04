<?php

use lore\mvc\ViewController;

require_once __DIR__ . "/../../models/User.php";

class UserController extends ViewController
{
    public function createNewModelInstance()
    {
        return new User();
    }

    /**
     * @uri /form
     */
    public function form(){
        $this->render("user/form.php");
    }

    /**
     * @uri /save
     * @method post
     */
    public function save(){
        if($this->loadAndValidateModel(\lore\mvc\ValidationModes::ALL)){
            $this->redirect("/");
        }else{
            $this->putModelAsArrayInResponse();
            $this->render("user/form.php");
        }
    }

}