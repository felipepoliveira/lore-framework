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

    public function load(Model $model, Request $request)
    {
        //Will store all data sent in request
        $data = $_GET;

        //If the request method is post or put merge the $_POST array
        if($request->isPost() || $request->isPut()){
            $data = array_merge($data, $_POST);
        }

        $this->loadRecursive($model, $data);
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
            $propName = $className . "." . $prop->getName();
            if(isset($array[$propName])){

                //Get the value from data array
                $arrayValue = $array[$propName];

                //If the data is an array, check if the property is a array...
                if(is_array($arrayValue) && $propClassName = ReflectionManager::propertyIsObject($prop, $model)){
                    $reflectionClass = new \ReflectionClass($propClassName);
                    $this->loadRecursive($reflectionClass->newInstance(), $arrayValue);
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
}