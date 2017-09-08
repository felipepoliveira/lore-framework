<?php
namespace lore\mvc;


use lore\Lore;

abstract class Mvc
{
    /**
     * @var \ReflectionClass
     */
    private static $reflectionClass;

    private static function modelReflectionClass($model){
        if(self::$reflectionClass === null){
            self::$reflectionClass = new \ReflectionClass(get_class($model));
        }

        return self::$reflectionClass;
    }

    public static function prop($prop){
        $model = Lore::app()->getResponse()->getData()["model"] ?? false;
        if($model){
            $ref = self::modelReflectionClass($model);
            try{
                return $ref->getMethod('get' . ucfirst($prop))->invoke($model);
            }catch(\Exception $e){
                die($e->getMessage());
            }
        }else{
            return "";
        }
    }
}