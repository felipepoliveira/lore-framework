<?php
namespace lore\mvc;

require_once __DIR__ . "/../ReflexiveMvcRouter.php";
require_once __DIR__ . "/../../utils/DocCommentUtil.php";
require_once __DIR__ . "/../../web/Response.php";

use lore\Lore;
use lore\util\DocCommentUtil;
use lore\util\ReflectionManager;

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
        $uri = DocCommentUtil::readAnnotationValue($method->getDocComment(), "uri");
        $httpMethod = DocCommentUtil::readAnnotationValue($method->getDocComment(), "method");

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

                    if(strlen($explodedUri[$i]) > 0 && $explodedUri[$i][0] === "$"){
                        $args[] = $explodedAction[$i];
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

        return null;
    }

    public  function getControllerMethodArguments()
    {
        return $this->methodArguments;
    }

}