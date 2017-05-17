<?php
namespace lore\mvc;

use lore\util\DocCommentUtil;

require_once __DIR__ . "/../ModelValidator.php";
require_once __DIR__ . "/../../utils/DocCommentUtil.php";


class ReflexiveModelValidator extends ModelValidator
{
    public function validate(Model $model, $validationMode, $validationArgs)
    {
        $errors = [];
        $className = get_class($model);
        $reflectionClass = new \ReflectionClass($className);

        //Lower the class name
        $className = strtolower($className);

        foreach ($reflectionClass->getProperties() as $prop) {
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

            //Store if the property can be accessed externaly
            $canAccess = $prop->isPublic();

            //Enable access to property
            if(!$canAccess){
                $prop->setAccessible(true);
            }

            //Make the validation
            $validationResult = self::validateProperty($prop, $model, $className);
            if($validationResult !== true){
                $errors["$className." . $prop->getName()] = $validationResult;
            }

            //Disable acess to property
            if(!$canAccess){
                $prop->setAccessible(false);
            }
        }

        echo "<pre>"; die(var_dump($errors));

        if(count($errors) > 0){
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
    public static function validateSpecific($docComment, $callback, $value, $useValidationValue = false, $prop){
        if(self::validateCallback(DocCommentUtil::readAnnotation($prop->getDocComment(), $docComment),
                $callback,
                $value,
                $useValidationValue))
        {
            return true;
        }else{
            return "Failed on validation: $docComment";
        }
    }

    public static function validateCallback($docCommentValue, $callback, $value, $useValidationValue = false){
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
    public static function validateProperty(\ReflectionProperty $prop, Model $model){
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