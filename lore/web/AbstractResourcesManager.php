<?php
namespace lore\web;

/**
 * Class AbstractResourcesManager - Abstract class that defines how the server can or can not send files to
 * the client
 * @package lore\web
 */
abstract class AbstractResourcesManager
{
    public abstract function isAResource(Request $request);

    public abstract function isAllowed(Request $request);

    public abstract function resourceExists(Request $request);

    public abstract function handle(Request $request);
}