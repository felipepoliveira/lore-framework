<?php

use lore\persistence\Entity;
use lore\mvc\Model;

/**
 * Class User
 */
class User extends Model
{
    use Entity;

    /**
     * @auto
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
     * @return int
     */
    public function getId(): int
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
    public function getName(): string
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
    public function getEmail(): string
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = hash("sha256", $password);
    }
}