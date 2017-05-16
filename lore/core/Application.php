<?php
namespace lore;

use lore\util\ReflectionManager;
use lore\web\ResourcesManager;
use lore\web\Request;
use lore\web\Response;
use lore\web\ResponseManager;
use lore\web\Router;

require_once "ApplicationContext.php";
require_once "Configurations.php";
require_once __DIR__ . "/../utils/ReflectionManager.php";
require_once __DIR__ . "/../web/ResourcesManager.php";
require_once __DIR__ . "/../web/Request.php";
require_once __DIR__ . "/../web/Response.php";

//AUTOLOAD
require_once __DIR__ . "/../web/Session.php";

/**
 * Class Application - Store data to be used in all scope of the system. An singleton instance of this class can be found
 * In the Lore::app() function.
 * @package lore
 */
class Application
{
    /**
     * @var ApplicationContext
     */
    private $context;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ResourcesManager
     */
    private $resourcesManager;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var ResponseManager
     */
    private $responseManager;

    /**
     * @var Router
     */
    private $router;

    /**
     * Flag that indicates if the application was already been loaded
     * @var bool
     */
    private $loaded = false;

    /**
     * It is not recommended to instance an object of this class. Use the Lore:app() to access the singleton object
     * of this class
     * Application constructor.
     */
    function __construct()
    {
        $this->loadConfigurations();
        $this->context = new ApplicationContext();
        $this->request = new Request($this->context);
        $this->router = $this->loadRouter();
        $this->responseManager = $this->loadResponseManager();
        $this->resourcesManager = $this->loadResourcesManager();
    }

    /**
     * Get the application context that the system is running
     * @see ApplicationContext
     * @return ApplicationContext
     */
    public function getContext(): ApplicationContext
    {
        return $this->context;
    }

    /**
     * Get the request data made by the user. This object is loaded automatically when an instance of Application is
     * created.
     * @see Request
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the response data processed by the server application. This object is loaded automatically after the
     * requisition is processed by the Router object.
     * @see Response
     * @see Router
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Get the router that process the client request. The router class is abstract and the implementations can be
     * any class that extend it. To define witch class will be the application router go to project.php config file
     * @see Request
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return ResourcesManager
     */
    public function getResourcesManager(): ResourcesManager
    {
        return $this->resourcesManager;
    }

    /**
     * Load the application processing the request and creating the response object. This method can be only called once.
     * The script responsible to do so is the bootstrap.php, that is called in any request that the server receives.
     */
    public function load(){
        if(!$this->loaded){
            $this->loaded = true;
            $this->handleRequest();
            $this->handleResponse();
        }
    }

    /**
     * Load the project.php config file into Configurations class. This method is called automatically in the constructor
     * of this class
     */
    private function loadConfigurations(){
        Configurations::load("project", __DIR__ . "/../../app/config/project.php");
    }

    /**
     * Load the ResponseManager defined in the project.php config file.
     * @return ResponseManager
     */
    private function loadResponseManager(){
        return ReflectionManager::instanceFromFile( Configurations::get("project", "responseManager")["class"],
            Configurations::get("project", "responseManager")["file"]);
    }

    private function loadResourcesManager(){
        return ReflectionManager::instanceFromFile( Configurations::get("project", "resourcesManager")["class"],
            Configurations::get("project", "resourcesManager")["file"], $this->request);
    }

    /**
     * Load the Router defined in the project.php config file.
     * @return Router
     */
    private function loadRouter(){
        return ReflectionManager::instanceFromFile( Configurations::get("project", "router")["class"],
            Configurations::get("project", "router")["file"]);
    }

    /**
     * Call the Router::route method and pass the Application::request as parameter to it. The method will always
     * return an Response object that will be stored in Application::response.
     */
    protected function handleRequest(){
        //If the request do not request an resource route the request
        if(!$this->resourcesManager->isAResource($this->request)){
            $this->response = $this->router->route($this->request);
        }else{
            //otherwise the resource manager will handle the request
            $this->response = $this->resourcesManager->handle($this->request);
        }
    }

    /**
     * Call the ResponseManager::handle method passing the Application::response as parameter to it. This method will
     * send the response stored in this class to the client
     */
    protected function handleResponse(){
        $this->responseManager->handle($this->response);
    }
}