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

    public static function error($errorCode, $html){

        if( Lore::app()->getResponse()->hasErrors() &&
            isset(Lore::app()->getResponse()->getErrors()[$errorCode]))
        {
            $errors = Lore::app()->getResponse()->getErrors()[$errorCode];

            if(is_array($errors)){
                $concatStr = "";
                foreach ($errors as $error){
                    $concatStr .= str_replace("%%", $error, $html);
                }
                return $concatStr;
            }else{
                return str_replace("%%", $errors, $html);
            }
        }else{
            return "";
        }
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