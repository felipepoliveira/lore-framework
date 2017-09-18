<?php
namespace lore\persistence;


abstract class Entity
{
    /**
     * @return mixed
     */
    public abstract function getIdentifier();
}