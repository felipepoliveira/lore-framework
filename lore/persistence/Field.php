<?php
namespace lore\persistence;


/**
 * Store the data about the field of an entity in repository.
 * Objects of this class could be found inside the EntityMetadata object.
 * Class Field
 * @package lore\persistence
 * @see EntityMetadata
 */
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
     * @var bool
     */
    private $identifier = false;

    /**
     * @var bool
     */
    private $auto = false;

    /**
     * The name of the field in repository
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define the name of the field in repository
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return the type name of the field
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Defines the type name of the field
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Return an flag indicating if the field is a identification field
     * @return bool
     */
    public function isIdentifier(): bool
    {
        return $this->identifier;
    }

    /**
     * Defines if the field is a identification field
     * @param bool $identifier
     */
    public function setIdentifier(bool $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Return an flag indicating if the repository defines the value of this field automatically
     * (Will not be loaded inside the application with the entity data)
     * @return bool
     */
    public function isAuto(): bool
    {
        return $this->auto;
    }

    /**
     * Defines if the field has the value defined automatically in repository
     * @param bool $auto
     */
    public function setAuto(bool $auto)
    {
        $this->auto = $auto;
    }

    /**
     * Return the name of the property of the entity associated with the field
     * @return mixed
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * Defines the property name associated with the field
     * @param mixed $propertyName
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

}