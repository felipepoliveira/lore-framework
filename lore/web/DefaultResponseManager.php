<?php
namespace lore\web;

require_once "ResponseManager.php";

/**
 * Class DefaultResponseManager - Implementation of ResponseManager that handle the response that will be sended
 * to the client.
 * @package lore
 */
class DefaultResponseManager extends ResponseManager
{
    /**
     * Generic method that handle the response send by the Application::router. It is based on these rules:
     * 1º: If the response has an uri and is configured as redirect: call DefaultResponseManager::redirect
     * method. This method set the header('location') to the Response::uri;
     * 2º: If the response has an uri and is not configured as redirect: call DefaultResponseManager::render method.
     * This method render the response to a given script stored in the Response::uri;
     * 3º: If the response does not have an uri: Call DefaultResponseManager::send method. This method 'echo'
     * the Response::data to the client.
     * @param Response $response - The response that will handled by this class
     */
    public function handle($response)
    {
        if(is_string($response->getUri())) {
            if ($response->isRedirect()) {
                $this->redirect($response);
            } else {
                $this->render($response);
            }
        }else{
            $this->send($response);
        }
    }

    /**
     * @param Response $response
     */
    protected function send($response){
        //Define the response code
        http_response_code($response->getCode());

        //Send the data if it was informed
        if($response->getData() != null){
            echo $response->getData();
        }
    }

    /**
     * @param Response $response
     */
    protected function redirect($response){
        header("location:" . $response->getUri());
    }

    /**
     * @param Response $response
     */
    protected function render($response){
        //Define the status to the request
        http_response_code($response->getCode());

        //Extract the variables that data and the errors that will be send to the page
        if(is_array($response->getData())) {
            extract($response->getData());
        }

        //Send the data
        $uri = $response->getUri();
        require "$uri";
    }

}