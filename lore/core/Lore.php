<?php
namespace lore;

require_once "Application.php";
require_once "DataStorage.php";

/**
 * Class Lore - The lore entry interface. This class contains objects and methods that can be accessed globally.
 * The main object of this class is the Lore::app() where the user can find all kinds of resources like:
 * An Request object that contains data of the Request sent by the client, access by: Lore::app()->getRequest();
 *
 * To load all the request data, the server must create an server script (like .htaccess) to the bootstrap.php.
 * This script call the method Lore::app()->load() that dispatch an chain of events...
 * The Lore::app() Create an singleton instance of the Application object and return it
 * The Lore::app()->load() Load all the environment of the framework, like: receive Request data, handle it,
 * handle Response, handle resource files, apply web filters, etc.
 * @package lore
 */
abstract class Lore
{
    /**
     * The application $app singleton
     * @var Application
     */
    private static $app;

    /**
     * Store global data in the server
     * @var DataStorage
     */
    private static $serverData;

    /**
     * Create the Application singleton and return it. This is the main interface of the framework to access
     * all kinds of singleton data, like: Lore::app()->getRequest(), Lore::app()->getResponseManager(), etc.
     * @return Application
     */
    public static function app(){
        if(!isset(Lore::$app)){
            Lore::$serverData = new DataStorage();
            Lore::$app = new Application();
        }

        return Lore::$app;
    }

    /**
     * Store server data to be used globally in application. To use this method you must use like:
     * Lore::serverData()->store($key, $value);
     * @return DataStorage
     */
    public static function serverData() : DataStorage
    {
        return self::$serverData;
    }

    /**
     * Return an relative path to application app root
     * @param $path
     * @return string
     */
    public static function path($path) : string {
        return Lore::app()->getContext()->getRelativePath() . "/app/" . $path;
    }

    /**
     * Return an path relative to /<domain>/<appRoot>/$path
     * @param $path Path to create the relative uri
     * @return string
     */
    public static function url($path) : string{
        return Lore::app()->getContext()->getRelativePath() . "/$path";
    }
}