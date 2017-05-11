<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 08/05/2017
 * Time: 11:17
 */

namespace lore;


abstract class Router
{
    /**
     * Route the request to an entity that handles the request
     * @param Request $request - The request object
     * @return Response - The response given bythe router
     */
    public abstract function route($request) : Response;
}