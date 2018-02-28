<?php
namespace lore;

require_once "ConfigurationException.php";

/**
 * Stores and return the data inside the configuration files
 * Class Configurations
 * @package lore
 */
abstract class Configurations
{
    /**
     * Store the configuration file data
     * @var array
     */
    private static $configurations = [];

    /**
     * Check if a configuration exists
     * @param $key
     * @param $configKey
     * @return bool
     */
    public static function contains($key, $configKey){
        $configs = Configurations::$configurations;

        if(!isset($configs[$key])){
            return false;
        }else if(!isset($configs[$key][$configKey])){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Load an configuration file and return its value
     * @param $key string  - An unique key to identify the configuration file
     * @param $file string - The configuration file path
     * @return array
     */
    public static function load($key, $file = null){

        if(isset($file) || $file === null){
            $file = Lore::app()->getContext()->getAbsolutePath() . "/app/config/$key.php";
        }

        if(file_exists($file)){
            $config = require "$file";
            Configurations::$configurations[$key] = $config;

            return $config;
        }else{
            throw new ConfigurationException("The file $file was not found while trying to load the
        configurations.");
        }

    }

    /**
     * Return an configuration value stored in the configurations scripts.
     * @param $key - The configuration file key name
     * @param $configKey - The configuration key that will be returned
     * @return string
     * @throws ConfigurationException - If the configuration file or configuration is not found
     */
    public static function get($key, $configKey){
        $configs = Configurations::$configurations;

        if(!isset($configs[$key])){
            throw new ConfigurationException("The file \"$key\" was not already loaded.");
        }else if(!isset($configs[$key][$configKey])){
            throw new ConfigurationException("The configuration \"$configKey\" was not found inside the file
            \"$key\". Please, create the configuration or check if it is commented");
        }else{
            return $configs[$key][$configKey];
        }
    }
}