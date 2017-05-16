<?php
namespace lore\mvc;


use lore\Configurations;
use lore\util\ReflectionManager;
use lore\web\Request;

//INCLUDES
require_once "ValidatorMessageProvider.php";

abstract class Model
{
    /**
     * @var ModelValidator
     */
    private static $validator = null;

    /**
     * @return ModelValidator
     */
    public static function validator(){
        $validator = &Model::$validator;
        if($validator === null){
            $validator = ReflectionManager::instanceFromFile( Configurations::get("mvc", "models")["validator"]["file"],
                                                                    Configurations::get("mvc", "models")["validator"]["class"]);
        }

        return $validator;
    }

    public function load(Request $request){

    }

    /**
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool|array
     */
    public function validate($validationMode = null, $validationExceptions = null){
        return Model::validator()->validate($this, $validationMode, $validationExceptions);
    }
}