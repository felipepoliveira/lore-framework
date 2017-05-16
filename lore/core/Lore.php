<?php
namespace lore;

require_once "Application.php";

abstract class Lore
{
    /**
     * @var Application
     */
    private static $app;

    /**
     * The application singleton
     * @return Application
     */
    public static function app(){
        if(!isset(Lore::$app)){
            Lore::$app = new Application();
        }

        return Lore::$app;
    }

    /**
     * Return an relative path to application app root
     * @param $path
     * @return string
     */
    public static function res($path) : string {
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