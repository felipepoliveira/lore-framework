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

    /**
     * Load all application repositories. The repositories must be configured in the
     * 'persistence.php' file with the needed data request by the Repository implementation that is being
     * used to persist the data
     * @return void
     */
    protected abstract function loadRepositories();

    /**
     * Return an repository used in application.
     * If the $repName parameter is null, it will be returned the first repository configured in persistence
     * configuration file. If the application has more than one repository the $repName must be declared to
     * return the specific one
     * @param mixed $repName
     * @return Repository
     */
    public abstract function getRepository($repName = null) : Repository;


}