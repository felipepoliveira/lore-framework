<?php
namespace lore\mvc;

require_once "ValidationModes.php";


abstract class ModelValidator
{
    function __construct()
    {
    }

    /**
     * Validate the model. Return true is validation is OK or an array with the errors if it is not.
     * @param Model $model
     * @param int $validationMode
     * @param array $validationArgs
     * @return true|array
     * @see ValidationModes
     */
    public abstract function validate(Model $model, $validationMode, $validationArgs);

    /**
     * Use filter_var function to check if the $value match the given $filter.
     * The values of the filter can be:
     * email: filter, where it uses filter_var($value, FILTER_VALIDATE_EMAIL) to validate
     * boolean: filter, where it uses filter_var($value, FILTER_VALIDATE_BOOLEAN) to validate
     * float: filter, where it uses filter_var($value, FILTER_VALIDATE_FLOAT) to validate
     * int: filter, where it uses filter_var($value, FILTER_VALIDATE_INT) to validate
     * ip: filter, where it uses filter_var($value, FILTER_VALIDATE_IP) to validate
     * mac: filter, where it uses filter_var($value, FILTER_VALIDATE_MAC) to validate
     * regexp: filter, where it uses filter_var($value, FILTER_VALIDATE_REGEXP) to validate
     * url: filter, where it uses filter_var($value, FILTER_VALIDATE_URL) to validate
     * @param $value - The value to be validated
     * @param $filter - The filter to be used in validation
     * @return bool
     */
    public static function filterVariable($value, $filter){
        switch (strtolower($filter)){
            case "email":
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            case "boolean":
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case "float":
                return filter_var($value, FILTER_VALIDATE_FLOAT);
            case "int":
                return filter_var($value, FILTER_VALIDATE_INT);
            case "ip":
                return filter_var($value, FILTER_VALIDATE_IP);
            case "mac":
                return filter_var($value, FILTER_VALIDATE_MAC);
            case "regexp":
                return filter_var($value, FILTER_VALIDATE_REGEXP);
            case "url":
                return filter_var($value, FILTER_VALIDATE_URL);
            default:
                return false;
        }
    }

    /**
     * Validate an value based on an given maximum value.
     * If the value is numeric it uses <, <=, > and >= operators to make the validation
     * If the value is string it check the length of the string using the strlen($value) function
     * If the value is an array it check the numbers of elements using the count($value) function
     * Otherwise it returns true
     * @param int $max - The max value
     * @param mixed $value - The value to be checked
     * @return bool
     */
    public static function validateMax($value, $max){
        if(is_numeric($value)){
            return $value <= $max;
        }else if(is_string($value)){
            return strlen($value) <= $max;
        }else if(is_array($value)){
            return count($value) <= $max;
        }else{
            return true;
        }
    }

    /**
     * Validate an value based on an given minimum value.
     * If the value is numeric it uses <, <=, > and >= operators to make the validation
     * If the value is string it check the length of the string using the strlen($value) function
     * If the value is an array it check the numbers of elements using the count($value) function
     * Otherwise it returns true
     * @param int $min - The max value
     * @param mixed $value - The value to be checked
     * @return bool
     */
    public static function validateMin($value, $min){
        if(is_numeric($value)){
            return $value >= $min;
        }else if(is_string($value)){
            return strlen($value) >= $min;
        }else if(is_array($value)){
            return count($value) >= $min;
        }else{
            return ($value !== null);
        }
    }

    /**
     * Validate if value is not null
     * @param mixed $value - The value to be checked
     * @return bool
     */
    public static function validateNotNull($value){
        return $value !== null;
    }

    /**
     * Return an flag indicating if an variable is empty, that is:
     * if $value is null or not set: return false;
     * if $value is a string, returns flag indicating if the length of the string is different of 0
     * if $value is a array, returns flag indicating if the number of elements is different of 0
     * If is not any of those options: returns true
     * @param $value
     * @return bool
     */
    public static function validateEmpty($value){
        if(isset($value) && $value != null){
            if(is_string($value)){
                return strlen($value) != 0;
            } else if(is_array($value)){
                return count($value) != 0;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}