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
     * Validation for max value
     * @param int $max - The max value
     * @param mixed $value - The value to be checked
     * @return bool
     */
    public function validateMax($value, $max){
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
     * Validation for max value
     * @param int $min - The max value
     * @param mixed $value - The value to be checked
     * @return bool
     */
    public function validateMin($value, $min){
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
    public function validateNotNull($value){
        return $value !== null;
    }
}