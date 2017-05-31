<?php
namespace lore\mvc;

require_once "NoModelLoaderDefinedException.php";


use lore\Configurations;
use lore\util\ReflectionManager;
use lore\web\Request;

abstract class Model
{
    /**
     * @var ModelValidator
     */
    private $validator = null;

    /**
     * @var ModelLoader
     */
    private $loader = null;

    function __construct()
    {
        $this->loader = $this->loadLoader();
        $this->validator = $this->loadValidator();
    }

    protected function loadLoader(){
        if(isset(Configurations::get("mvc", "models")["loader"])){
            return ReflectionManager::instanceFromFile(
                Configurations::get("mvc", "models")["loader"]["class"],
                Configurations::get("mvc", "models")["loader"]["file"]
            );
        }else{
            return null;
        }
    }

    /**
     * Load the ModelValidator from the configuration files
     * @return ModelValidator|null
     */
    protected function loadValidator(){
        if(isset(Configurations::get("mvc", "models")["validator"]))
        {
            return ReflectionManager::instanceFromFile(
                Configurations::get("mvc", "models")["validator"]["class"],
                Configurations::get("mvc", "models")["validator"]["file"]);
        }else{
            return null;
        }
    }

    /**
     * @return ModelLoader
     */
    public function getLoader(): ModelLoader
    {
        return $this->loader;
    }

    /**
     * Validator singleton
     * @return ModelValidator
     */
    public function getValidator(){
        return $this->validator;
    }

    /**
     * Check if loader is defined in configuration file
     */
    private function isLoaderLoaded(){
        return $this->loader !== null;
    }

    /**
     * Check if validator is defined in configuration file
     * @return bool
     */
    private function isValidatorLoaded(){
        return $this->validator !== null;
    }

    /**
     * @param Request $request
     */
    public function load(Request $request){
        if($this->isLoaderLoaded()){
            $this->loader->load($this, $request);
        }
    }

    /**
     * Serialize the model to an array. If an model loader is defined this method use it to do the serialization.
     * Otherwise an exception will be thrown
     * @param $args
     * @return array
     */
    public function toArray(...$args) : array {
        if($this->isLoaderLoaded()){
            return $this->getLoader()->toArray($this);
        }else{
            throw new NoModelLoaderDefinedException("An model loader is needed to do the array serialization. Check
            if an ModelLoader implementation is defined in config/mvc.php file");
        }
    }

    /**
     * Call Model::validator to validated the model. If the validator is not loaded (Model::isValidatorLoaded used)
     * return false automatically
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool|array
     */
    public function validate($validationMode = null, $validationExceptions = null){
        if($this->isValidatorLoaded()){
            return $this->getValidator()->validate($this, $validationMode, $validationExceptions);
        }else{
            return true;
        }
    }
}