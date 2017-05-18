<?php
namespace lore\mvc;


use lore\Lore;

abstract class Mvc
{
    public function data($prop){
        $model = Lore::app()->getResponse()->getData()["model"] ?? false;
        if($model){
            
        }
    }
}