<?php
namespace lore\mvc;
require_once "Controller.php";
require_once "Model.php";
require_once __DIR__ . "/../web/Router.php";

use lore\Configurations;
use lore\web\Response;
use lore\web\Router;


abstract class MvcRouter extends Router
{
    /**
     * @var string[]
     */
    private $viewsDirectories;

    /**
     * @var string[]
     */
    private $controllersDirectories;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $actionName;

    function __construct()
    {
        Configurations::load("mvc", __DIR__ . "/../../app/config/mvc.php");
        $this->controllersDirectories = Configurations::get("mvc", "controllers")["dirs"];
        $this->viewsDirectories = Configurations::get("mvc", "views")["dirs"];
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @return \string[]
     */
    public function getControllersDirectories(): array
    {
        return $this->controllersDirectories;
    }

    /**
     * @return \string[]
     */
    public function getViewsDirectories(): array
    {
        return $this->viewsDirectories;
    }

    /**
     * Explode the request uri separating in the [0] index the name of the controller and in the [1] the name of the action
     * @param  string $uri The uri to be exploded
     * @return array
     */
    protected function explodeUri($uri){
        //Filter the array of blank spaces
        $explodedRawUri = array_filter(explode("/", $uri), function($e){
            return $e !== '';
        });

        //Remove get parameters from uri
        $countExplodedRawUri = count($explodedRawUri);
        for ($i = 1; $i <= $countExplodedRawUri; $i++){
            $pos = strrpos($explodedRawUri[$i], "?");
            if($pos){
                $explodedRawUri[$i] = substr($explodedRawUri[$i], 0, $pos);
            }
        }

        //If the controller name is not found set 'index' as default controller name
        $explodedUri = [$explodedRawUri[1] ?? "index"];

        //Get the rest of the action
        $action = "";
        for($i = 2; $i <= $countExplodedRawUri; $i++){
            $action .= $explodedRawUri[$i];
            if($i < $countExplodedRawUri){
                $action .= "/";
            }
        }

        //Put the action in the exploded array and return it
        $explodedUri[] = $action;

        return $explodedUri;
    }

    public  function route($request): Response
    {
        $explodedUri = $this->explodeUri($request->getRequestedUri());;

        //Get and format the controller name
        $this->controllerName = ucfirst($explodedUri[0]) . "Controller";
        //Get the action name
        $this->actionName = $explodedUri[1];

        $this->controller = $this->searchController($this->controllerName);

        if($this->controller !== null){
            return $this->dispatchToController($this->controller, $this->actionName, $request);
        }else{
            return new Response(null, false, 404);
        }
    }

    /**
     * @param $controller
     * @param $actionName
     * @param $request
     * @return Response
     */
    public abstract function dispatchToController($controller, $actionName, $request): Response;

    /**
     * @param string $controllerName The name of the controller
     * @return Controller
     */
    public abstract function searchController($controllerName);
}