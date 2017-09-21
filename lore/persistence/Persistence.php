<?php
namespace lore\persistence;

abstract class Persistence
{
    function __construct()
    {
        $this->loadRepositories();
    }

    protected abstract function loadRepositories();

    public abstract function getRepository($repName = null) : Repository;


}