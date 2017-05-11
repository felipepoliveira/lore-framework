<?php
namespace lore\mvc;


abstract class Model
{
    /**
     * @return bool|array
     */
    public function validate(){
        return true;
    }

    public function validationRules(){
        return [];
    }
}