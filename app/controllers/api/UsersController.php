<?php

require_once __DIR__ . "/../../models/User.php";

use lore\mvc\ApiController;

class UsersController extends ApiController
{
    public function createNewModelInstance()
    {
        return new User();
    }

    /**
     * @uri /
     * @method post
     */
    public function register(){

        if($this->loadAndValidateModel()){
            $this->send(null, 201);
        }else {
            $this->send(null, 400);
        }
    }
}