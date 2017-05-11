<?php
namespace lore;

use lore\util\ReflectionManager;

require_once "ApplicationContext.php";
require_once "Configurations.php";
require_once __DIR__ . "/../utils/ReflectionManager.php";
require_once "Request.php";


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

    function __construct()
    {
        $this->loadConfigurations();
        $this->context = new ApplicationContext();
        $this->request = new Request($this->context);
        $this->router = $this->loadRouter();
        $this->responseManager = $this->loadResponseManager();
    }

    /**
     * @return ApplicationContext
     */
    public function getContext(): ApplicationContext
    {
        return $this->context;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    public function load(){
        if(!$this->loaded){
            $this->loaded = true;
            $this->handleRequest();
            $this->handleResponse();
        }
    }

    private function loadConfigurations(){
        Configurations::load("project", __DIR__ . "/../../app/config/project.php");
    }

    private function loadResponseManager(){
        return ReflectionManager::instanceFromFile( Configurations::get("project", "responseManager")["class"],
            Configurations::get("project", "responseManager")["file"]);
    }

    private function loadRouter(){
        return ReflectionManager::instanceFromFile( Configurations::get("project", "router")["class"],
            Configurations::get("project", "router")["file"]);
    }

    protected function handleRequest(){
        $this->response = $this->router->route($this->request);
    }

    protected function handleResponse(){
        $this->responseManager->handle($this->response);
    }
}