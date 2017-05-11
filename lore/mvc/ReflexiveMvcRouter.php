<?php
namespace lore\mvc;

require_once "MvcRouter.php";
require_once __DIR__ .  "/../utils/File.php";
require_once __DIR__ .  "/../utils/ReflectionManager.php";

use lore\util\ReflectionManager;
use lore\Response;
use lore\util\File;

abstract class ReflexiveMvcRouter extends MvcRouter
{
    public  function dispatchToController($controller, $actionName, $request): Response
    {
        $method = $this->searchMethod($this->controller, $this->getActionName(), $request);

        if($method != null){
            $args = $this->getControllerMethodArguments();
            $method->invokeArgs($this->controller, $args);
            return $this->controller->getResponse();
        }else{
            return new Response(null, false, 404);
        }
    }

    public function searchController($controllerName){
        //Get the controller file name
        $controllerFile = "$controllerName.php";
        $controllerFile = File::checkFileInDirectories($controllerFile, $this->getControllersDirectories(), true);

        //If the file exists load it
        if($controllerFile){
            try{
                //Pass this object as parameter to the controller
                $controller = ReflectionManager::instanceFromFile($controllerName, $controllerFile, $this, false);
                return $controller;
            }catch(\Exception $e){}
        }

        //Return null if the controller was not found
        return null;
    }

    /**
     * @param  Controller$controller - The controller object that will invoke the method
     * @param string $actionName - The name of the action (used to identify the method to be invoked
     * @param Request $request
     * @return \ReflectionMethod
     */
    public abstract function searchMethod($controller, $actionName, $request);

    /**
     * The arguments that will be used to invoke the controller method
     * @return mixed
     */
    public abstract function getControllerMethodArguments();
}