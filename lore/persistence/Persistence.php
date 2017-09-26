<?php
namespace lore\persistence;

/**
 * Represents the maximum abstraction of the persistence module.
 * The implementation of this class must load the repositories used in application.
 * An repository is an service class that persists entities. This repository must be a implementation of
 * Repository class and must implements all the methods to persist and read the entities from the repository
 *
 * The implementation of this class must be configured in project configuration file in "persistence" key
 *
 * @package lore\persistence
 */
abstract class Persistence
{
    function __construct()
    {
        $this->loadRepositories();
    }

    protected abstract function loadRepositories();

    public abstract function getRepository($repName = null) : Repository;


}