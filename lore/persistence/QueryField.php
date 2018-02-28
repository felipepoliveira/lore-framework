<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 14/10/2017
 * Time: 15:49
 */

namespace lore\persistence;


class QueryField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var Field
     */
    private $field;

    function __construct(string $name, string $entityName, Field $field)
    {
        $this->name = $name;
        $this->entityName = $entityName;
        $this->field = $field;
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
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }
}