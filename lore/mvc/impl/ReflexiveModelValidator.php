<?php
namespace lore\mvc;

use lore\Lore;
use lore\util\DocCommentUtil;

require_once __DIR__ . "/../ModelValidator.php";
require_once __DIR__ . "/../../utils/DocCommentUtil.php";


class ReflexiveModelValidator extends ModelValidator
{
    /**
     * Store the class name of the current validation class
     * @var string
     */
    private $className;

    function __construct()
    {
        //Load the validation string file if it is enabled
        if(Lore::app()->isStringProviderEnabled()){
            Lore::app()->getStringProvider()->loadStrings("validations.php");
        }
    }

    /**
     * Return an message from the string provider object
     * @param $msgCode
     * @return string
     */
    protected function getMessageFromStringProvider($msgCode, $value){
        $return = Lore::app()->getStringProvider()->getString($msgCode);
        $return = str_replace("{value}", $value, $return); //Replace {value} tag

        return $return;
    }

    /**
     * Return the message error of the validation
     * @param string $validationAnnotation - The validation annotation name
     * @param string $propName - The name of validated property
     * @param $docCommentValue - The value of the validation annotation
     * @return string
     */
    protected function getMessageError(string $validationAnnotation, string $propName, $docCommentValue) : string {
        //Store the default message and the message code
        $defaultMsg = "Failed on validation: $validationAnnotation";
        $msgCode = $this->className . "." . $propName . "." . $validationAnnotation;

        //Get message from message provider or return the default message
        if(Lore::app()->isStringProviderEnabled() && Lore::app()->getStringProvider()->hasString($msgCode)){
            return $this->getMessageFromStringProvider($msgCode, $docCommentValue);
        }else{
            return $defaultMsg;
        }
    }

    public function validate(Model $model, $validationMode, $validationArgs)
    {
        $errors = [];
        $this->className = get_class($model);
        $reflectionClass = new \ReflectionClass($this->className);

        //Iterate over the model properties...
        foreach ($reflectionClass->getProperties() as $prop) {

            //Store if the property can be accessed externaly
            $canAccess = $prop->isPublic();

            //Enable access to property
            if(!$canAccess){
                $prop->setAccessible(true);
            }

            //If the property is another model, call validate it validate method
            if(is_object($prop->getValue($model))){
                $return = $prop->getValue($model)->validate($validationMode, $validationArgs);

                if($return !== true){
                    $errors = array_merge($errors, $return);
                }

                continue;
            }

            //Check validation mode
            switch ($validationMode){
                //If is on except mode and the name of the property is in the validation args go to another prop
                case ValidationModes::EXCEPT:
                    if(in_array($prop->getName(), $validationArgs)){
                        continue 2;
                    }
                    break;
                //If in on only mode and the name of the property is not on the validation args go to another prop
                case ValidationModes::ONLY:
                    if(!in_array($prop->getName(), $validationArgs)){
                        continue 2;
                    }
                    break;
            }
            //Make the validation
            $validationResult = $this->validateProperty($prop, $model);

            //If validation is not ok, store the errors in array
            if($validationResult !== true){
                $errors[$this->className . "." . $prop->getName()] = $validationResult;
            }

            //Disable acess to property
            if(!$canAccess){
                $prop->setAccessible(false);
            }
        }

        //If there is no errors, return true, otherwise return the array with the errors
        if(count($errors) == 0){
            return true;
        }else{
            return $errors;
        }
    }

    /**
     * @param string $docComment
     * @param callable $callback
     * @param string $value
     * @param bool $useValidationValue
     * @param \ReflectionProperty $prop
     * @return string|true
     */
    public function validateSpecific($docComment, $callback, $value, $useValidationValue = false, $prop){
        $docCommentValue = DocCommentUtil::readAnnotation($prop->getDocComment(), $docComment);
        if(self::validateCallback(
            $docCommentValue,
            $callback,
            $value,
            $useValidationValue))
        {
            return true;
        }else{
            return $this->getMessageError($docComment, $prop->getName(), $docCommentValue);
        }
    }

    public function validateCallback($docCommentValue, $callback, $value, $useValidationValue = false){
        if($docCommentValue){
            if(isset($useValidationValue)){
                return $callback($value, $docCommentValue);
            }else{
                return $callback($value);
            }
        }else{
            return true;
        }
    }

    /**
     * Validate an property  of an object
     * @param \ReflectionProperty $prop
     * @param Model $model
     * @return array|true
     */
    public function validateProperty(\ReflectionProperty $prop, Model $model){
        //Store errors
        $errors = [];

        //Validating max
        $errors["max"] = self::validateSpecific(
            "max",
            function($v, $a){return ModelValidator::validateMax($v, $a);},
            $prop->getValue($model),
            true,
            $prop);

        //Validating min
        $errors["min"] = self::validateSpecific(
            "min",
            function($v, $a){return ModelValidator::validateMin($v, $a);},
            $prop->getValue($model),
            true,
            $prop);

        //Validate not null
        $errors["notNull"] =  self::validateSpecific(
            "notNull",
            function($v){return ModelValidator::validateNotNull($v);},
            $prop->getValue($model),
            false,
            $prop);

        //Validate regex
        $errors["regex"] =  self::validateSpecific(
            "regex",
            function($v, $r){return preg_match($r, $v);},
            $prop->getValue($model),
            true,
            $prop);

        //Filter where errors where not found
        $errors = array_filter($errors, function($e){
            return $e !== true;
        });

        if(count($errors) > 0){
            return $errors;
        }else{
            return true;
        }
    }
}