<?php

class UserController extends \lore\mvc\ApiController
{
    /**
     * @uri /
     */
    public function index(){
        $this->send([
           "msg" => "Olá mundo!"
        ]);
    }
}