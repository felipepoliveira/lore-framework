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
            return ReflectionManager::reflectionClass($class)->newInstance($args);
        }else{
            throw new \ReflectionException("The file containing the class \"$class\" was not found in path: $file");
        }
    }

    protected static function reflectionClass($className){
        if(ReflectionManager::$reflectionClass === null || ReflectionManager::$reflectionClass->getName() !== $className){
            ReflectionManager::$reflectionClass = new \ReflectionClass($className);
        }

        return ReflectionManager::$reflectionClass;
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
}