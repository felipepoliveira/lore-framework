<?php
use lore\mvc\Model;
use lore\mvc\ValidatorMessageProvider;

class User extends Model
{
    /**
     * @var int
     * @number
     */
    private $id;
    /**
     * @var string
     * @max 20
     * @min 2
     */
    private $name;
    /**
     * @var string
     * @max 60
     * @min 2
     */
    private $lastName;
    /**
     * @var string
     * @regex /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
     */
    private $email;
    /**
     * @var string
     * @min 6
     * @max 30
     */
    private $password;

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
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
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

    public function hashPassword(){
        $this->setPassword(hash("sha256", $this->getPassword()));
    }
}