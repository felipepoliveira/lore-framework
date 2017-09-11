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
     * @var RouteRule
     */
    protected $routeRules = [];

    protected function loadRouteRules(){
        $routeRules = Configurations::get("project", "router")["rules"] ?? [];

        foreach ($routeRules as $uri => $script) {
            $this->routeRules[] = new RouteRule(Lore::app()->getRequest(), $uri, $script);
        }
    }

    /**
     * Route the request to an entity that handles the request
     * @param Request $request - The request object
     * @return Response - The response given by the router
     */
    public abstract function route($request) : Response;
}