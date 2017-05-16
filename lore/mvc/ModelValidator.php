<?php
namespace lore\mvc;

require_once "ValidationModes.php";


abstract class ModelValidator
{
    /**
     * Validate the model. Return true is validation is OK or an array with the errors if it is not.
     * @param Model $model
     * @param int $validationMode
     * @param array $validationArgs
     * @return true|array
     * @see ValidationModes
     */
    public abstract static function validate(Model $model, $validationMode, $validationArgs);
}