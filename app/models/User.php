<?php

use lore\persistence\Entity;
use lore\mvc\Model;

require_once "Address.php";

/**
 * @repository lore/mysql
 */
class User
{
    use Entity;

    /**
     * @auto
     * @id
     * @field
     * @var int
     */
    private $id;

    /**
     * @field
     * @max 80
     * @min 3
     * @var string
     */
    private $name;

    /**
     * @field
     * @filterVar email
     * @max 120
     * @min 3
     * @var string
     */
    private $email;

    /**
     * @field
     * @min 8
     * @var string
     */
    private $password;

    /**

     * @one
     * @var Address
     */
    private $address;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    public function hashPassword(){
        $this->password = hash("sha256", $this->password);
    }
}