<?php

namespace lore;

use lore\persistence\Persistence;
use lore\persistence\Repository;
use lore\util\ReflectionManager;
use lore\web\ResourcesManager;
use lore\web\Request;
use lore\web\Response;
use lore\web\ResponseManager;
use lore\web\Router;
use lore\web\ViewPreProcessor;

require_once "ApplicationContext.php";
require_once "Configurations.php";
require_once "ModuleException.php";
require_once __DIR__ . "/../persistence/Persistence.php";
require_once __DIR__ . "/../utils/ReflectionManager.php";
require_once __DIR__ . "/../web/ResourcesManager.php";
require_once __DIR__ . "/../web/Request.php";
require_once __DIR__ . "/../web/Response.php";

//AUTOLOAD
require_once __DIR__ . "/../web/Session.php";
require_once __DIR__ . "/../utils/Arrays.php";

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
     * @var ObjectLoader
     */
    private $objectLoader;

    /**
     * @var ObjectValidator
     */
    private $objectValidator;

    /**
     * @var Persistence
     */
    private $persistence;

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
     * @var StringProvider
     */
    private $stringProvider;

    /**
     * @var ViewPreProcessor
     */
    private $viewPreProcessor;

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
        $this->context = new ApplicationContext();
    }

    /**
     * Load application's components
     */
    protected function loadComponents()
    {
        $this->responseManager = $this->loadResponseManager();
        $this->request = new Request($this->context);
        $this->router = $this->loadRouter();
        $this->resourcesManager = $this->loadResourcesManager();
    }

    /**
     * Load application's modules
     */
    protected function loadModules()
    {
        $this->persistence = $this->loadPersistence();
        $this->objectLoader = $this->loadObjectLoader();
        $this->objectValidator = $this->loadObjectValidator();
        $this->stringProvider = $this->loadStringProvider();
        $this->viewPreProcessor = $this->loadViewPreProcessor();
    }

    /**
     * @return ObjectLoader
     */
    public function getObjectLoader(): ObjectLoader
    {
        return $this->objectLoader;
    }

    /**
     * @return ObjectValidator
     */
    public function getObjectValidator(): ObjectValidator
    {
        return $this->objectValidator;
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
     * Get the persistence module object
     * @return Persistence
     */
    public function getPersistence(): Persistence
    {
        return $this->persistence;
    }

    /**
     * Return an flag indicating if the persistence module is enabled
     * @return bool
     */
    public function isPersistenceEnabled(): bool
    {
        return $this->persistence !== null;
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
     * Return the response manager object of the application
     * @return ResponseManager
     */
    public function getResponseManager()
    {
        return $this->responseManager;
    }

    /**
     * Return the default string provider of the application. This object can be null if the string provider module
     * is not enabled in project
     * @return StringProvider
     */
    public function getStringProvider()
    {
        return $this->stringProvider;
    }

    /**
     * * Return the default string provider of the application. This object can be null if the string provider module
     * is not enabled in project
     * @return ViewPreProcessor
     */
    public function getViewPreProcessor()
    {
        return $this->viewPreProcessor;
    }

    /**
     * Check if StringProvider module is implemented in Application
     * @return bool
     */
    public function isStringProviderEnabled(): bool
    {
        return $this->stringProvider !== null;
    }

    /**
     * return flag indicating if the object loader module is implemented in application
     * @return bool
     */
    public function isObjectLoaderEnabled(): bool
    {
        return $this->objectLoader != null;
    }

    /**
     * return flag indicating if the object validator module is implemented in application
     * @return bool
     */
    public function isObjectValidatorEnabled(): bool
    {
        return $this->objectValidator != null;
    }

    public function isVPPEnabled(): bool
    {
        return $this->viewPreProcessor != null;
    }

    /**
     * Load the application processing the request and creating the response object. This method can be only called once.
     * The script responsible to call this method is the bootstrap.php, that is called in any request that the server receives.
     */
    public function load()
    {
        if (!$this->loaded) {
            $this->loadConfigurations();
            $this->loadModules();
            $this->loadComponents();

            $this->loaded = true;
            $this->handleRequest();
            $this->handleResponse();
        }
    }

    /**
     * Load the project.php config file into Configurations class. This method is called automatically in the constructor
     * of this class
     */
    private function loadConfigurations()
    {
        Configurations::load("app", __DIR__ . "/../../app/config/app.php");
        $this->context->loadApplicationState();
    }

    /**
     * Load the ObjectLoader defined in the configuration file project.php in "object" => "loader" configuration]
     * THIS MODULE CAN BE NULL
     */
    private function loadObjectLoader()
    {

        if (Configurations::contains("app", "objectLoader")) {
            return ReflectionManager::instanceFromFile(
                Configurations::get("app", "objectLoader")["class"],
                Configurations::get("app", "objectLoader")["file"]);
        } else {
            return null;
        }
    }

    /**
     * Load the ObjectValidator defined in the configuration file project.php in "object" => "validator" configuration
     * THIS MODULE CAN BE NULL
     */
    private function loadObjectValidator()
    {
        if (Configurations::contains("app", "object") && Configurations::get("app", "object")["loader"]) {
            return ReflectionManager::instanceFromFile(
                Configurations::get("app", "object")["validator"]["class"],
                Configurations::get("app", "object")["validator"]["file"]);
        } else {
            return null;
        }
    }

    private function loadPersistence()
    {
        if (Configurations::contains("app", "persistence")) {
            return ReflectionManager::instanceFromFile(
                Configurations::get("app", "persistence")["class"],
                Configurations::get("app", "persistence")["file"]);
        } else {
            return null;
        }
    }

    /**
     * Load the ResponseManager defined in the project.php config file.
     * @return ResponseManager
     */
    private function loadResponseManager()
    {
        return ReflectionManager::instanceFromFile(Configurations::get("app", "responseManager")["class"],
            Configurations::get("app", "responseManager")["file"]);
    }

    /**
     * Load the ResourcesManager defined in the project.php config file in: "resourceManager" property
     * @return ResourcesManager
     */
    private function loadResourcesManager()
    {
        return ReflectionManager::instanceFromFile(Configurations::get("app", "resourcesManager")["class"],
            Configurations::get("app", "resourcesManager")["file"], $this->request);
    }

    /**
     * Load the Router defined in the project.php config file.
     * @return Router
     */
    private function loadRouter()
    {
        return ReflectionManager::instanceFromFile(Configurations::get("app", "router")["class"],
            Configurations::get("app", "router")["file"]);
    }

    /**
     * Load the StringProvider of the application in project.php config file
     * @return StringProvider|null
     */
    private function loadStringProvider()
    {
        if (Configurations::contains("app", "stringProvider")) {
            return ReflectionManager::instanceFromFile(
                Configurations::get("app", "stringProvider")["class"],
                Configurations::get("app", "stringProvider")["file"]
            );
        } else {
            return null;
        }
    }

    public function loadViewPreProcessor()
    {
        if (Configurations::contains('app', 'viewPreProcessor') && Configurations::get('app', 'viewPreProcessor')["enabled"] === true) {
            return ReflectionManager::instanceFromFile(
                Configurations::get("app", "viewPreProcessor")["class"],
                Configurations::get("app", "viewPreProcessor")["file"]
            );
        } else {
            return null;
        }
    }

    /**
     * Call the Router::route method and pass the Application::request as parameter to it. The method will always
     * return an Response object that will be stored in Application::response.
     */
    protected function handleRequest()
    {
        //If the request do not request an resource route the request
        if (!$this->resourcesManager->isAResource($this->request)) {
            $this->response = $this->router->route($this->request);
        } else {
            //otherwise the resource manager will handle the request
            $this->response = $this->resourcesManager->handle($this->request);
        }
    }

    /**
     * Call the ResponseManager::handle method passing the Application::response as parameter to it. This method will
     * send the response stored in this class to the client
     */
    protected function handleResponse()
    {
        $this->responseManager->handle($this->response);
    }
}