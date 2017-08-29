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
     */
    public function teste(){
        $this->loadModel();
        $this->send([
            "model" => $this->getModel()
        ]);
    }
}