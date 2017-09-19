<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 18/09/2017
 * Time: 21:09
 */

namespace lore\persistence;


interface ISqlTranslator
{
    public function insert($entity) : string;
}