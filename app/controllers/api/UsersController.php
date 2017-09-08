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
     * @uri /$id
     * @method get
     */
    public function save($id){
        if(isset($id)){

        }else{

        }
    }
}