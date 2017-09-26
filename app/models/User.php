<?php
use lore\mvc\Model;

/**
 * Class User
 * @entity user
 * @repository todo/mysql
 */
class User extends Model
{
    use \lore\persistence\Entity;

    /**
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
     * @max 120
     * @varFilter email
     * @var string
     */
    private $email;

    /**
     * @field
     * @max 30
     * @min 6
     * @var string
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
    public function setId($id)
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
    public function setName($name)
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
    public function setEmail($email)
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
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function hashPassword(){
        $this->password = hash("sha256", $this->getPassword());
    }
}