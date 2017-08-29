<?php
namespace lore\mvc;

use lore\DataStorage;
use lore\Lore;
use lore\web\DataFormatter;

class View
{
    /**
     * @return DataStorage
     */
    public static function metadata() : DataStorage
    {
        return Lore::serverData();
    }

    public static function actionName(){
        return self::metadata()->get("action");
    }

    /**
     * Return an response data stored in Response object.
     * @param $code
     * @return string|null
     */
    public static function data($code){
        return Lore::data($code);
    }

    public static function error($errorCode, $html, $condition = true){
        return Lore::error($errorCode, $html, $condition);
    }


    public static function input($modelAttr, $attrs){
        return "<input $attrs name=model.$modelAttr value='".self::data($modelAttr)."'>";
    }

    /**
     * Return an relative path to application app root
     * @param $path
     * @return string
     */
    public static function path($path) : string {
        return Lore::path($path);
    }

    /**
     * Return an path relative to /<domain>/<appRoot>/$path
     * @param $path Path to create the relative uri
     * @return string
     */
    public static function url($path) : string{
        return Lore::url($path);
    }
}