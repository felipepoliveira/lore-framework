<?php
namespace lore\mvc;

use lore\util\DocCommentUtil;

require_once __DIR__ . "/../ModelValidator.php";
require_once __DIR__ . "/../../utils/DocCommentUtil.php";


class ReflexiveModelValidator extends ModelValidator
{
    public static function validate(Model $model, $validationMode, $validationArgs)
    {
        $errors = [];
        $className = get_class($model);
        $reflectionClass = new \ReflectionClass($className);

        //Lower the class name
        $className = strtolower($className);

        foreach ($reflectionClass->getProperties() as $prop) {
            switch ($validationMode){
                case ValidationModes::EXCEPT:
                    break;
                case ValidationModes::ONLY:
                    break;
            }

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
     * @param string $className
     * @return string|true
     */
    public static function validateSpecific($docComment, $callback, $value, $useValidationValue = false, $prop, $className){
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
    public static function validateProperty(\ReflectionProperty $prop, Model $model, $className){
        //Store errors
        $errors = [];

        //Validating max
        $errors["max"] = self::validateSpecific(
            "max",
            function($v, $a){return ModelValidator::validateMax($v, $a);},
            $prop->getValue($model),
            true,
            $prop,
            $className);

        //Validating min
        $errors["min"] = self::validateSpecific(
            "min",
            function($v, $a){return ModelValidator::validateMin($v, $a);},
            $prop->getValue($model),
            true,
            $prop,
            $className);

        //Validate not null
        $errors["notNull"] =  self::validateSpecific(
            "notNull",
            function($v){return ModelValidator::validateNotNull($v);},
            $prop->getValue($model),
            false,
            $prop,
            $className);

        //Validate regex
        $errors["regex"] =  self::validateSpecific(
            "regex",
            function($v, $r){return preg_match($r, $v);},
            $prop->getValue($model),
            true,
            $prop,
            $className);

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