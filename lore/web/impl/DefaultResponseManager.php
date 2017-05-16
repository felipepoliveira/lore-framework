<?php
namespace lore\web;

require_once __DIR__ . "/../ResponseManager.php";

/**
 * Class DefaultResponseManager - Implementation of ResponseManager that handle the response that will be sended
 * to the client.
 * @package lore
 */
class DefaultResponseManager extends ResponseManager
{

    protected function sendResource(Response $response){
        readfile($response->getUri());
    }

    protected function service(Response $response){
        //Send the data if it was informed
        if($response->getData() != null){
            echo $response->getData();
        }
    }

    protected function redirect(Response $response){
        header("location:" . $response->getUri());
    }

    protected function render(Response $response){
        //Extract the variables that data and the errors that will be send to the page
        if(is_array($response->getData())) {
            extract($response->getData());
        }

        //Send the data
        $uri = $response->getUri();
        require "$uri";
    }

}