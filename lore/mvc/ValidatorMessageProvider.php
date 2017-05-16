<?php
namespace lore\mvc;


interface ValidatorMessageProvider
{
    /**
     * Return an array with the validation messages errors.
     * @return array
     */
    public function validationsMessages() : array;
}