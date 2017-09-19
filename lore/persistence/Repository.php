<?php
namespace lore\persistence;

require_once "Entity.php";
require_once "PersistenceException.php";

abstract class Repository
{

    /**
     * @var string
     */
    protected $name;

    /**
     * Repository constructor.
     * Load an repository with the given name (an unique name) and the
     * configuration data
     * @param $name string - The name of the repository
     * @param $data mixed - The data for repository load
     */
    function __construct($name, $data)
    {
        $this->name = $name;
        $this->loadData($data);
    }

    /**
     * The repository name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public abstract function loadData($data);

    /**
     * Delete an entity from the repository
     * @param $entity
     * @return bool Flag indicating if the deletion was succeeded
     * @throws PersistenceException
     */
    public abstract function delete($entity) : bool ;

    /**
     * Return an flag indicating if the given $entity already exists in the repository
     * @param $entity Entity
     * @return bool
     */
    public abstract function exists($entity) : bool ;

    /**
     * Insert an entity into repository
     * @param $entity Entity
     * @return
     * @throws PersistenceException - If an errors occurs in the repository while inserting the new entity
     */
    public abstract function insert($entity);

    /**
     * Create an query syntax object with the methods to build queries
     * @see QuerySyntax
     * @return QuerySyntax
     */
    public abstract function query() : QuerySyntax;

    /**
     * Update an existing entity into repository
     * @param $entity Entity
     */
    public abstract function update($entity);

    /**
     * Save the entity in the repository. If the entity already exists it makes an Repository::update, otherwise
     * it Repository::insert the $entity
     * @param Entity $entity
     * @return mixed
     */
    public function save($entity){
        if($this->exists($entity)){
            $this->update($entity);
        }else{
            $this->insert($entity);
        }
    }
}