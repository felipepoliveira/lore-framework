<?php
namespace lore;

require_once "ResponseManager.php";

class DefaultResponseManager extends ResponseManager
{
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