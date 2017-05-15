<?php
namespace lore\web;


abstract class Router
{
    /**
     * Route the request to an entity that handles the request
     * @param Request $request - The request object
     * @return Response - The response given by the router
     */
    public abstract function route($request) : Response;
}