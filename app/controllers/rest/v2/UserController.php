<?php

use lore\mvc\ApiController;

/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 12/09/2017
 * Time: 08:25
 */
class UserController extends ApiController
{
    /**
     * @uri /gui
     * @method [post, get]
     */
    public function teste(){
        $this->loadModel();
        $this->putModelAsArrayInResponse();
        $this->send([
            "msg" => "Olá"
        ]);
    }
}