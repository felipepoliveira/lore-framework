<?php

require_once __DIR__ . "/../../models/User.php";

use lore\mvc\ApiController;

class UsersController extends ApiController
{

    /**
     * @var User[]
     */
    private $users = [];

    public function createNewModelInstance()
    {
        return new User();
    }

    /**
     * @uri /
     * @method get
     */
    public function buscarTodos(){
        $this->users[] = new User(0, "a", "11111-1111");
        $this->users[] = new User(1, "b", "21111-1111");
        $this->users[] = new User(2, "c", "31111-1111");
        $this->users[] = new User(3, "D", "41111-1111");
        $this->users[] = new User(4, "e", "51111-1111");

        $this->send($this->users, 200);
    }

    /**
     * @uri /$id
     * $method get
     */
    public function buscar($id){
        $this->users[] = new User(0, "a", "11111-1111");
        $this->users[] = new User(1, "b", "21111-1111");
        $this->users[] = new User(2, "c", "31111-1111");
        $this->users[] = new User(3, "D", "41111-1111");
        $this->users[] = new User(4, "e", "51111-1111");

        $this->send($this->users[$id], 200);
    }


}