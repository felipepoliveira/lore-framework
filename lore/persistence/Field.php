<?php
namespace lore\persistence;


class Field
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var bool
     */
    private $identifier = false;

    /**
     * @var bool
     */
    private $auto = false;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isIdentifier(): bool
    {
        return $this->identifier;
    }

    /**
     * @param bool $identifier
     */
    public function setIdentifier(bool $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return bool
     */
    public function isAuto(): bool
    {
        return $this->auto;
    }

    /**
     * @param bool $auto
     */
    public function setAuto(bool $auto)
    {
        $this->auto = $auto;
    }

    /**
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param mixed $propertyName
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

}