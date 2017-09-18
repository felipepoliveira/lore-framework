<?php

class TestController extends \lore\mvc\ViewController
{
    public  function createNewModelInstance()
    {
        // TODO: Implement createNewModelInstance() method.
    }

    /**
     * @uri /thayto
     * @method post
     */
    public function teste(){
        $this->render("test.php");
    }
}