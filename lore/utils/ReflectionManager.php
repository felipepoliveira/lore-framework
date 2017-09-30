<?php
namespace lore\util;

/**
 * General purpose reflection methods
 * Class ReflectionManager
 * @package lore\util
 */
abstract class ReflectionManager
{
    /**
     * @var \ReflectionClass
     */
    private static $reflectionClass;

    /**
     * Make an instance based on an class located in an specific file
     * @param $class - The name of the class
     * @param $file string Relative path (based on project root) to class file to be instanced
     * @param $args mixed - Arguments to the object instance
     * @param bool $absolute - Define if the sent $file is absolute
     * @return mixed
     * @throws \ReflectionException if the file containing the class does not exists or the class was not found
     */
    public static function instanceFromFile($class, $file, $args = null, $absolute = true){
        $file = ($absolute) ? __DIR__ . "/../../" . $file : $file;

        if(file_exists($file)){
            require_once "$file";
            if(isset($args) && is_array($args)){
                return ReflectionManager::reflectionClass($class)->newInstanceArgs($args);
            }else{
                return ReflectionManager::reflectionClass($class)->newInstance($args);
            }
        }else{
            throw new \ReflectionException("The file containing the class \"$class\" was not found in path: $file");
        }
    }

    public static function newInstance($className, $args = null){
        if(isset($args) && is_array($args)){
            return ReflectionManager::reflectionClass($className)->newInstanceArgs($args);
        }else{
            return ReflectionManager::reflectionClass($className)->newInstance($args);
        }
    }

    protected static function reflectionClass($className){
        if(ReflectionManager::$reflectionClass === null || ReflectionManager::$reflectionClass->getName() !== $className){
            ReflectionManager::$reflectionClass = new \ReflectionClass($className);
        }

        return ReflectionManager::$reflectionClass;
    }

    public static function invokeGetOf(string $property, $object, \ReflectionClass $refClass = null){
        if(!isset($refClass)){
            $refClass = ReflectionManager::reflectionClass(get_class($object));
        }

        try{
            $method = $refClass->getMethod("get" .  ucfirst($property));
            return $method->invoke($object);
        }catch (\Exception $e){
            throw new \ReflectionException("The property \"$property\" of class " . $refClass->getName() . " does not have
            an get method");
        }
    }

    public static function invokeSetOf(string $property, $value, $object, \ReflectionClass $refClass = null){
        if(!isset($refClass)){
            $refClass = ReflectionManager::reflectionClass(get_class($object));
        }

        try{
            $method = $refClass->getMethod("set" .  ucfirst($property));
            return $method->invokeArgs($object, [$value]);
        }catch (\Exception $e){
            throw new \ReflectionException("The property \"$property\" of class " . $refClass->getName() . " does not have
            an get method");
        }
    }

    /**
     * Invoke an method of an given object
     * @param object $object The object
     * @param string $methodName The name of the method
     * @param mixed $args The method arguments
     * @return mixed The return of the object method
     */
    public static function invokeMethod($object, $methodName, $args = null){
        return ReflectionManager::reflectionClass(get_class($object))->getMethod($methodName)->invokeArgs($object, $args);
    }

    /**
     * @param $object
     * @return \ReflectionMethod[]
     */
    public static function listMethods($object){
        return ReflectionManager::reflectionClass(get_class($object))->getMethods();
    }

    public static function propertyIsArray(\ReflectionProperty $prop, $model){
        $var =  DocCommentUtil::readAnnotationValue($prop->getDocComment(), "var");
        if($var){
            return ($var === "array" || strpos($var, "[]") !== false);
        }else{
            return is_array($prop->getValue($model));
        }
    }

    public static function propertyIsBoolean(\ReflectionProperty $prop, $model){
        $var =  DocCommentUtil::readAnnotationValue($prop->getDocComment(), "var");
        if($var){
            return ($var === "bool" || $var === "boolean");
        }else{
            return is_array($prop->getValue($model));
        }
    }

    public static function propertyIsNumber(\ReflectionProperty $prop, $model){
        $var =  DocCommentUtil::readAnnotationValue($prop->getDocComment(), "var");
        if($var){
            return ($var === "double" || $var === "float" || $var === "int" || $var === "integer");
        }else{
            return is_numeric($prop->getValue($model));
        }
    }

    public static function propertyIsObject(\ReflectionProperty $prop, $model){
        $var =  DocCommentUtil::readAnnotationValue($prop->getDocComment(), "var");
        if($var){
            if (!self::propertyIsArray($prop, $model) && !self::propertyIsBoolean($prop, $model)&&
                !self::propertyIsString($prop, $model) && !self::propertyIsNumber($prop, $model)){
                return $var;
            }else{
                return false;
            }
        }else{
            return is_object($prop->getValue($model));
        }
    }

    public static function propertyIsString(\ReflectionProperty $prop, $model){
        $var =  DocCommentUtil::readAnnotationValue($prop->getDocComment(), "var");
        if($var){
            return ($var === "string");
        }else{
            return is_string($prop->getValue($model));
        }
    }
}