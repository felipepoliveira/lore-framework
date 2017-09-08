<?php
namespace lore\mvc;

use lore\Configurations;
use lore\Lore;
use lore\util\DocCommentUtil;
use lore\util\File;
use lore\util\ReflectionManager;
use lore\web\Request;

require_once __DIR__ . "/../ModelLoader.php";


class ReflexiveModelLoader extends ModelLoader
{

    /**
     * @var array
     */
    private $viewsDirectories;

    function __construct()
    {
        $this->viewsDirectories = Configurations::get("mvc", "models")["dirs"];
    }

    public function load($model, Request $request)
    {
        $this->loadRecursive($model, $request->requestDataAsRecursiveArray());
    }

    /**
     * Load the object recursively interacting over the encapsulated objects
     * @param Model $model
     * @param array $array
     */
    protected function loadRecursive(Model $model, array $array){
        //Store the name of the class
        $className = get_class($model);

        //Create reflection object
        $reflectionClass = new \ReflectionClass($className);
        $className = strtolower($className);

        //Iterate over all object property
        foreach ($reflectionClass->getProperties() as $prop) {

            //Store the accessibility of the property. If it is not accessible open the accessibility
            $isPublic = $prop->isPublic();
            if(!$isPublic){
                $prop->setAccessible(true);
            }

            //Check if the property exists in the array data...
            $propName = $prop->getName();
            if(isset($array[$propName])){

                //Get the value from data array
                $arrayValue = $array[$propName];

                //If the data is an array, check if the property is a array...
                if(is_array($arrayValue) && $propClassName = ReflectionManager::propertyIsObject($prop, $model)){
                    $reflectionClass = new \ReflectionClass($propClassName);
                    $prop->setValue($model, $reflectionClass->newInstance());
                    $this->loadRecursive($prop->getValue($model), $arrayValue);
                }else{
                    $prop->setValue($model, $arrayValue);
                }
            }

            //Go back to not accessible mode
            if(!$isPublic){
                $prop->setAccessible(false);
            }
        }
    }

    public function toArray($obj, $plainMode = false) : array
    {
        if($plainMode){
            return $this->toArrayRecursive($obj);
        }else{
            $array = [];
            return $this->toArrayPlain($obj, $array, "");
        }
    }

    public function toArrayRecursive($obj) : array {
        $array = [];

        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($reflectionClass->getProperties() as $prop) {
            if(!$prop->isPublic()){
                $prop->setAccessible(true);
            }

            $propVal = $prop->getValue($obj);

            if(is_object($propVal)){
                $array[$prop->getName()] = $this->toArrayRecursive($propVal);
            }else{
                $array[$prop->getName()] = $propVal;
            }


            if(!$prop->isPublic()){
                $prop->setAccessible(false);
            }

        }
        return $array;
    }

    public function toArrayPlain($obj, &$array, $prefix) : array {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($reflectionClass->getProperties() as $prop) {
            if(!$prop->isPublic()){
                $prop->setAccessible(true);
            }

            $propVal = $prop->getValue($obj);

            if(is_object($propVal)){
                //Put the prefix
                $prefix = $prop->getName() . ".";

                $this->toArrayPlain($propVal, $array, $prefix);
            }else{
                $array[$prefix .  $prop->getName()] = $propVal;
            }


            if(!$prop->isPublic()){
                $prop->setAccessible(false);
            }

        }

        //Remove the last prefix
        $pos = strrpos($prefix, ".");
        if($pos){
            $prefix = substr($prefix, 0, $pos);
        }

        return $array;
    }
}