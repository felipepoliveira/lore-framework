<?php
use lore\mvc\Model;

require_once "Address.php";

class User extends Model
{

    function __construct($id = null, $name = null, $phone = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
    }

    /**
     * @var Address
     * @notNull
     */
    private $address;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @max 80
     * @min 2
     */
    private $name;

    /**
     * @var string
     * @regex /^[0-9]{10,11}$/
     */
    private $phone;



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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
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


}