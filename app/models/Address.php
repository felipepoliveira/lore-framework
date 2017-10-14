<?php

use lore\mvc\Model;

require_once "Country.php";

class Address
{
    use \lore\persistence\Entity;

    /**
     * @field
     * @auto
     * @id
     * @var int
     */
    private $id;

    /**
     * @field
     * @min 3
     * @max 60
     * @var string
     */
    private $publicPlace;

    /**
     * @one
     * @var Country
     */
    private $country;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPublicPlace()
    {
        return $this->publicPlace;
    }

    /**
     * @param string $publicPlace
     */
    public function setPublicPlace(string $publicPlace)
    {
        $this->publicPlace = $publicPlace;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

}