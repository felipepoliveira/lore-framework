<?php
namespace lore\mvc;


class ReflexiveModelValidator extends ModelValidator
{
    public static function validate(Model $model, $validationMode, $validationArgs)
    {
        $errors = [];
        $reflectionClass = new \ReflectionClass(get_class($model));

        foreach ($reflectionClass->getProperties() as $prop) {
            switch ($validationMode){
                case ValidationModes::EXCEPT:
                    break;
                case ValidationModes::ONLY:
                    break;
            }

            //Make the validation
            $validationResult = self::validateProperty($prop, $model);
            if($validationResult !== false){
                $errors[] = $validationResult;
            }
        }

        if(count($errors) > 0){
            return true;
        }else{
            return $errors;
        }
    }

    /**
     * Validate an property  of an object
     * @param \ReflectionProperty $prop
     * @param Model $model
     */
    public function validateProperty(\ReflectionProperty $prop, Model $model){

    }
}