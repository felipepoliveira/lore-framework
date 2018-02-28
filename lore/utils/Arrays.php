<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 01/10/2017
 * Time: 10:02
 */

namespace lore\util;


abstract class Arrays
{
    public static function toRecursiveArray(array $baseArray){
        $array = array();

        //Iterate over
        foreach ($baseArray as $key => $value) {
            self::lastRecursiveIterationOf($array, $key, $trueKey, $value);
        }

        return $array;
    }

    private static function lastRecursiveIterationOf(&$array, $key, &$trueKey, $value){
        //Check if the key has an composition value (separting . or _ )
        $pos = strpos($key, ".");
        if(!$pos) $pos = strpos($key, "_");

        if($pos){
            //Get the key before the first dot
            $keyBeforeDot = substr($key, 0, $pos);
            //Get the key after the first dot
            $trueKey = substr($key, $pos+1, strlen($key));

            //If the index is not defined craete an array
            if(!isset($array[$keyBeforeDot])){
                $array[$keyBeforeDot] = [];
            }

            //Iterate over the last index while the key has a dot
            self::lastRecursiveIterationOf($array[$keyBeforeDot], $trueKey, $trueKey, $value);
        }else{
            //If the key does not have a dot put the value into array
            $trueKey = $key;
            $array[$trueKey] = $value;
        }
    }
}