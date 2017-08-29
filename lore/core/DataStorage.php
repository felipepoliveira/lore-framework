<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 13/08/2017
 * Time: 18:15
 */

namespace lore;


class DataStorage
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Store data in current server request
     * @param $key - the key of the value
     * @param $value - The value of the data
     */
    public function store($key, $value){
        $this->data[$key] = $value;
    }


    public function get($key){
        return $this->data[$key] ?? null;
    }

    public function contains($key){
        return  $this->data[$key] !== null;
    }

    public function valueIs($key, $value){
        if($this->contains($key)){
            return $this->get($key) === $value;
        }else{
            return false;
        }
    }

}