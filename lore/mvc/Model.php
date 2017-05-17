<?php
namespace lore\mvc;


use lore\Configurations;
use lore\util\ReflectionManager;
use lore\web\Request;

abstract class Model
{
    /**
     * @var ModelValidator
     */
    private static $validator = null;

    /**
     * Validator singleton
     * @return ModelValidator
     */
    public static function validator(){
        $validator = &Model::$validator;
        if(isset(Configurations::get("mvc", "models")["validator"])){
            if($validator === null){
                $validator = ReflectionManager::instanceFromFile(
                    Configurations::get("mvc", "models")["validator"]["class"],
                    Configurations::get("mvc", "models")["validator"]["file"]);
            }
        }

        return $validator;
    }

    /**
     * Check if validator is defined in configuration files
     * @return bool
     */
    public static function isValidatorLoaded(){
        return self::validator() !== null;
    }

    public function load(Request $request){

    }

    /**
     * Call Model::validator to validated the model. If the validator is not loaded (Model::isValidatorLoaded used)
     * return false automatically
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool|array
     */
    public function validate($validationMode = null, $validationExceptions = null){
        if(Model::isValidatorLoaded()){
            return Model::validator()->validate($this, $validationMode, $validationExceptions);
        }else{
            return false;
        }
    }
}