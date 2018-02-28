<?php
namespace lore\web;

require_once __DIR__ . "/../ResponseManager.php";
require_once __DIR__ . "/../ViewPreProcessor.php";

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
        //Only echo the data if it exists...
        if($response->getData() !== null && (is_array($response->getData()) && count($response->getData()) > 0)){
            echo $this->dataFormatter->format($response->getData());
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