<?php
namespace lore\mvc;


abstract class Validator
{
    /**
     * Validate the model. Return true is validation is OK or an array with the errors if it is not
     * @param Model $model
     * @return true|array
     */
    public abstract static function validate(Model $model);
}