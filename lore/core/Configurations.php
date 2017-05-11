<?php
namespace lore;

require_once "ConfigurationNotFoundException.php";

/**
 * Stores and return the data inside the configuration files
 * Class Configurations
 * @package lore
 */
class Configurations
{
    /**
     * Store the configuration file data
     * @var array
     */
    private static $configurations = [];

    /**
     * Load an configuration file
     * @param $key string  - An unique key to identify the configuration file
     * @param $file string - The configuration file path
     */
    public static function load($key, $file){
        if(file_exists($file)){
            Configurations::$configurations[$key] = require "$file";
        }else{
            throw new ConfigurationNotFoundException("The file $file was not found while trying to load the
            configurations.");
        }
    }

    public static function get($key, $configKey){
        $configs = Configurations::$configurations;

        if(!isset($configs[$key])){
            throw new ConfigurationNotFoundException("The file \"$key\" was not already loaded.");
        }else if(!isset($configs[$key][$configKey])){
            throw new ConfigurationNotFoundException("The configuration \"$configKey\" was not found inside the file
            \"$key\". Please, create the configuration or check if it is commented");
        }else{
            return $configs[$key][$configKey];
        }
    }
}