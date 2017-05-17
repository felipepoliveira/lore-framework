<?php

use lore\mvc\Model;

/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 17/05/2017
 * Time: 17:27
 */
class Address extends Model
{
    /**
     * @var string
     */
    private $address;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }


}