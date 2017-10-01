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
        $data = Lore::app()->getResponse()->getData() ?? [];

        if(isset($data[$code])){
            return $data[$code];
        }else{
            return "";
        }
    }

    public static function error($errorCode, $html, $condition = true){

        if($condition){
            if( Lore::app()->getResponse()->hasErrors() &&
                isset(Lore::app()->getResponse()->getErrors()[$errorCode])) {
                $errors = Lore::app()->getResponse()->getErrors()[$errorCode];

                if(is_array($errors)){
                    $concatStr = "";
                    foreach ($errors as $error){
                        $concatStr .= str_replace("{{value}}", $error, $html);
                    }
                    return $concatStr;
                }else{
                    return str_replace("{{value}}", $errors, $html);
                }
            }else{
                return "";
            }
        }
    }

    /**
     * @return bool Flag indicating if errors was sent in the response
     */
    public static function hasErrors(){
        return Lore::app()->getResponse()->hasErrors();
    }

    /**
     * Return an html string if an condition is true
     * @param $html - The html that will be returned
     * @param bool $condition - The condition to return the html (default: true)
     * @return  string (The html or an empty string)
     */
    public static function html($html, $condition = true){
        if($condition){
            return $html;
        }else{
            return "";
        }
    }

    public static function input($modelAttr, $attrs = ""){
        return "<input $attrs name=$modelAttr value='".self::data("model.$modelAttr")."'>";
    }

    public static function textarea($modelAttr, $attrs = ""){
        return "<textarea $attrs name=$modelAttr value='".self::data("model.$modelAttr")."'></textarea>";
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
     * @param $path string to create the relative uri
     * @return string
     */
    public static function url($path) : string{
        return Lore::url($path);
    }
}