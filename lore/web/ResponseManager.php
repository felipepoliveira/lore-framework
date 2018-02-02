<?php
namespace lore\web;


use lore\Configurations;
use lore\util\ReflectionManager;

abstract class ResponseManager
{
    /**
     * @var DataFormatter
     */
    protected $dataFormatter;

    function __construct()
    {
        $this->loadDataFormatter();
    }

    /**
     * Get the data formatter object
     * @see DataFormatter
     * @return DataFormatter
     */
    public function getDataFormatter()
    {
        return $this->dataFormatter;
    }

    private function loadDataFormatter(){
        $this->dataFormatter =
            ReflectionManager::instanceFromFile(
            Configurations::get("app", "dataFormatter")["class"],
            Configurations::get("app", "dataFormatter")["file"]);
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
        //$response->getCacheHeader()->putHeader();
        foreach ($response->getHeaderEntities() as $headerEntity){
            $headerEntity->putHeader();
        }

        //If the uri is carrying some data (view, redirection uri or a file, etc.)
        if(is_string($response->getUri())) {

            //Check if the response is sending an resource (server file)
            if($response->isSendingResource()){
                $this->sendResource($response);

                //Check if the response is making an redirect
            }else if ($response->isRedirect()) {
                $this->redirect($response);
            } else {

                //Otherwise it will render the view
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