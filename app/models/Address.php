<?php
use lore\mvc\Model;

class Address extends Model
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     * @min 1
     * @max 60
     */
    private $publicPlace;

    /**
     * @var string
     * @min 3
     * @max 60
     */
    private $city;

    /**
     * @var integer
     * @min 1
     */
    private $number;

    /**
     * @var string
     */
    private $referencePoint;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
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
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getReferencePoint()
    {
        return $this->referencePoint;
    }

    /**
     * @param string $referencePoint
     */
    public function setReferencePoint(string $referencePoint)
    {
        $this->referencePoint = $referencePoint;
    }


}