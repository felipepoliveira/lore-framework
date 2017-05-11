<?php
namespace lore;


abstract class ResponseManager
{
    function __construct()
    {
    }

    /**
     * Handle the response
     * @param Response $response The response to be handled
     */
    public abstract function handle($response);
}