<?php
namespace lore\mvc;

use lore\Lore;
use lore\ModuleException;
use lore\web\Request;

abstract class Model
{
    /**
     * @param Request $request
     */
    public function load(Request $request){

        if(Lore::app()->isObjectLoaderEnabled()){
            Lore::app()->getObjectLoader()->load($this, $request);
        }else{
            throw new ModuleException("An object loader is needed to do the object loading. Check
            if an ObjectLoader implementation is defined in config/project.php file");
        }
    }

    /**
     * Serialize the model to an array. If an model loader is defined this method use it to do the serialization.
     * Otherwise an exception will be thrown
     * @param $args
     * @return array
     */
    public function toArray(...$args) : array {

        if(Lore::app()->isObjectLoaderEnabled()){
            return Lore::app()->getObjectLoader()->toArray($this);
        }else{
            throw new ModuleException("An object loader is needed to do the array serialization. Check
            if an ObjectLoader implementation is defined in config/project.php file");
        }
    }

    /**
     * Call Model::validator to validated the model. If the validator is not loaded (Model::isValidatorLoaded used)
     * return false automatically
     * @param int $validationMode
     * @param array $validationExceptions
     * @param string $prefix The prefix that will applied
     * @return bool|array
     */
    public function validate($validationMode = null, $validationExceptions = null, $prefix = ""){

        if(Lore::app()->isObjectValidatorEnabled()){
            return Lore::app()->getObjectValidator()->validate($this, $validationMode, $validationExceptions, $prefix);
        }else {
            throw new ModuleException("An object validator is needed to do model validation. Check
            if an ObjectValidator implementation is defined in config/project.php file");
        }
    }
}