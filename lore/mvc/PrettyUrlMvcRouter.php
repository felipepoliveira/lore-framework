<?php
namespace lore\mvc;

use lore\Lore;
use lore\util\DocCommentUtil;
use lore\util\ReflectionManager;

require_once "MvcRouter.php";
require_once "ReflexiveMvcRouter.php";
require_once __DIR__ . "/../core/Response.php";
require_once __DIR__ . "/../utils/DocCommentUtil.php";

class PrettyUrlMvcRouter extends ReflexiveMvcRouter
{

    /**
     * @var array
     */
    private $methodArguments = [];

    /**
     * @param \ReflectionMethod $method
     * @param string $actionName
     * @return array|bool
     */
    protected function methodMatchActionName($method, $actionName){
        $uri = DocCommentUtil::readAnnotation($method->getDocComment(), "uri");
        $httpMethod = DocCommentUtil::readAnnotation($method->getDocComment(), "method");

        //Check if the http method of the controller method is compative with the method of the request
        if($httpMethod && ! Lore::app()->getRequest()->is($httpMethod)){
            return false;
        }

        if($uri){
            $actionName = "/" . $actionName;

            $explodedUri = explode("/", $uri);
            $explodedAction = explode("/", $actionName);

            $countUri = count($explodedUri);
            $countAction = count($explodedAction);

            if($countAction === $countUri){

                $args = [];

                for($i = 0; $i < $countAction; $i++){
                    if(strlen($explodedAction[0]) > 0 && $explodedAction[$i][0] === "$"){
                        $args[] = substr($explodedAction[$i], 1, strlen($explodedAction[$i]));
                        continue;
                    }
                    if($explodedAction[$i] !== $explodedUri[$i]){
                        return false;
                    }
                }
                return $args;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function searchMethod($controller, $actionName, $request)
    {
        foreach (ReflectionManager::listMethods($controller) as $method){
            $args = $this->methodMatchActionName($method, $actionName);
            if($args !== false){
                $this->methodArguments = $args;
                return $method;
            }
        }
    }

    public  function getControllerMethodArguments()
    {
        return $this->methodArguments;
    }

}