<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 14/10/2017
 * Time: 08:31
 */

namespace lore\persistence;


class TransactionField
{
    /**
     * @var Field
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    function __construct(Field $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field)
    {
        $this->field = $field;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}