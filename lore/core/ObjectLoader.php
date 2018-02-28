<?php
namespace lore;

/**
 * Class ObjectLoader
 * @package lore\mvc
 */
abstract class ObjectLoader
{
    function __construct()
    {
    }

    /**
     * Load an model object with data from an array
     * @param $model - The model that will be loaded
     * @param array $data - The array with the data to be loaded in the object
     * @return void
     */
    public abstract function load($model, array $data);

    /**
     * Convert the model to an array
     * @param object $obj
     * @param bool $plainMode
     * @return array
     */
    public abstract function toArray($obj, $plainMode = false) : array ;
}