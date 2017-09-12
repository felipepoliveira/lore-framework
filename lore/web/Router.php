<?php
namespace lore\web;

use lore\Configurations;
use lore\Lore;

require_once "RouteRule.php";

abstract class Router
{
    function __construct()
    {
        $this->loadRouteRules();
    }

    /**
     * @var RouteRule[]
     */
    protected $routeRules = [];

    /**
     * Load all route rules from the project => router => rules configuration file
     */
    protected function loadRouteRules(){
        $routeRules = Configurations::get("project", "router")["rules"] ?? [];

        //Add each route rule from the configuration file into the route rules list
        foreach ($routeRules as $uri => $script) {
            $this->routeRules[] = new RouteRule($uri, $script);
        }
    }

    /**
     * Return the RouteRule that match with the requested sent by the client. If any route rule match the
     * request it returns false
     * @param $request
     * @return bool|RouteRule
     */
    public function matchRouteRule($request){
        foreach ($this->routeRules as $routeRule) {
            if($routeRule->match($request)) {
                return $routeRule;
            }
        }

        return false;
    }

    /**
     * Route the request to an entity that handles the request
     * @param Request $request - The request object
     * @return Response - The response given by the router
     */
    public abstract function route($request) : Response;

}