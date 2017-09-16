<?php

use lore\mvc\ApiController;

require_once __DIR__ . "/../../../models/Usuario.php";

/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 12/09/2017
 * Time: 08:25
 */
class UserController extends ApiController
{
    public function createNewModelInstance()
    {
        return new Usuario();
    }


    /**
     * @uri /teste
     */
   public function teste(){
       $this->loadModel();
       $this->sendModel();
   }
}