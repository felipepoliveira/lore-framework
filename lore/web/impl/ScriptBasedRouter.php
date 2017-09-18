<?php
namespace lore\web;

require_once __DIR__ . "/../Router.php";

use lore\ConfigurationException;
use lore\Lore;

class ScriptBasedRouter extends Router
{
    public function route($request): Response
    {
        $response = new Response();

        //Get the Rule that matches the request...
        $rule = $this->matchRouteRule($request);

        //Check if some rule matches the request
        if($rule){

            die($rule->produceUri($request));

            //Produce the URI from the Rule and put it into the request
            Lore::app()->getRequest()->setRequestedUri($rule->produceUri($request));

            //Get the scripts that matches the rule and use the first one to handle the request
            $scripts = $rule->getScripts();

            if(count($scripts) > 0){
                //Get the path to the script and check if exists...
                $pathToScript = Lore::app()->getContext()->getAbsolutePath() . "/" . $scripts[0];
                if(file_exists($pathToScript)){
                    $response->setUri($pathToScript);
                }else{
                    $response->setCode(404);
                }
            }else{
                throw new ConfigurationException("The rule " . $rule->getRouteRule() . " does not have 
                the script that will handle the request");
            }
        }else{
            $response->setCode(404);
        }

        return $response;
    }

}