<?php
namespace lore;

/**
 * Class DataStorage - Class that represents an data storage map.
 * @package lore
 */
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

    /**
     * Return an value stored associated with the given $key
     * @param $key mixed
     * @return mixed|null
     */
    public function get($key){
        return $this->data[$key] ?? null;
    }

    /**
     * Check if the data storage contains an given $key
     * @param $key mixed
     * @return bool
     */
    public function contains($key){
        return  $this->data[$key] !== null;
    }

    /**
     * Compare the value of the associated $key with an given $value
     * @param $key mixed
     * @param $value mixed
     * @return bool
     */
    public function valueIs($key, $value){
        if($this->contains($key)){
            return $this->get($key) === $value;
        }else{
            return false;
        }
    }

}