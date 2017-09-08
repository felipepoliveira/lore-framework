<?php

use lore\mvc\ViewController;

class IndexController extends ViewController
{
    public function createNewModelInstance()
    {
        return null;
    }

    /**
     * @uri /
     */
    public function index(){
        $teste['teste'] = "oi";
        $this->render("index.php",[
            'teste'=>'testando suave',
            'var'=>$teste
        ]);
    }

    /**
     * @uri /register
     */
    public function userRegister(){
        $this->render("user/form.php");
    }

}