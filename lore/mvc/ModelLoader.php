<?php
namespace lore\mvc;
use lore\web\Request;

/**
 * Class ModelLoader
 * @package lore\mvc
 */
abstract class ModelLoader
{
    function __construct()
    {
    }

    /**
     * Load an model object with data from request
     * @param $model - The model that will be loaded
     * @param $request - The request data
     * @return void
     */
    public abstract function load($model, Request $request);

    /**
     * Convert the model to an array
     * @param Model $obj
     * @param bool $plainMode
     * @return array
     */
    public abstract function toArray($obj, $plainMode = false) : array ;
}