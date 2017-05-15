<?php
namespace lore\web;


abstract class ResponseManager
{
    function __construct()
    {
    }

    /**
     * Handle the response processed by the server
     * @param Response $response The response to be handled
     */
    public abstract function handle($response);
}