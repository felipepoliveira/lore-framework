<?php
namespace lore\web;


abstract class ResponseManager
{
    function __construct()
    {
    }

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
        $charset = ($response->getCharset() != null)? "; charset=" . $response->getCharset() : "";

        //Set the content type and the response code
        header("Content-type:" . $response->getContentType() . $charset);
        http_response_code($response->getCode());

        //Put headers
        $response->getCache()->putHeader();

        if(is_string($response->getUri())) {
            if($response->isSendingResource()){
                $this->sendResource($response);
            }else if ($response->isRedirect()) {
                $this->redirect($response);
            } else {
                $this->render($response);
            }
        }else{
            $this->service($response);
        }
    }

    /**
     * Send an resource file (image, document, video, etc.) to the client as response
     * @param Response $response
     */
    protected abstract function sendResource(Response $response);

    /**
     * Redirect the request to another uri
     * @param Response $response
     */
    protected abstract function redirect(Response $response);

    /**
     * Send an page or script as response  the client
     * @param Response $response
     */
    protected abstract function render(Response $response);

    /**
     * Send an response to the client in form of pure data. Used in web services api for example.
     * @param Response $response
     */
    protected abstract function service(Response $response);
}