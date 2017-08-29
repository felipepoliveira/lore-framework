<?php
use lore\mvc\ViewController;
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 12/08/2017
 * Time: 10:36
 */
class UserController extends ViewController
{
    public  function createNewModelInstance()
    {
        return null;
    }

    /**
     * @uri /form
     */
    public function form(){
        $this->render("user/form.php");
    }
}