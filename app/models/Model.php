<?php

/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 10/05/2017
 * Time: 18:04
 */
abstract class Model
{
    /**
     * @return array|true
     */
    public abstract function validate();
}