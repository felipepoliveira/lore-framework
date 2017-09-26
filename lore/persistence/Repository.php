<?php
namespace lore\persistence;

require_once "Entity.php";
require_once "PersistenceException.php";
require_once "Query.php";

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

    /**
     * Load the repository data with the data set by the user in the persistence configuration file.
     * @param $data - The specific configuration of the repository
     * @return void
     */
    public abstract function loadData($data);

    /**
     * Delete an entity from the repository
     * @param $entity Entity|Entity[]
     * @return int - The number of affected rows
     * @throws PersistenceException
     */
    public abstract function delete($entity) : int ;

    /**
     * Return an flag indicating if the given $entity already exists in the repository
     * @param $entity Entity|Entity[]
     * @return bool
     */
    public abstract function exists($entity) : bool ;

    /**
     * Insert an entity(s) into repository
     * @param $entity Entity|Entity[]
     * @return
     * @throws PersistenceException - If an errors occurs in the repository while inserting the new entity
     */
    public abstract function insert($entity);

    /**
     * Create an query syntax object with the methods to build queries
     * @param $class \stdClass|null
     * @see Query
     * @return Query
     */
    public abstract function query($class = null) : Query;

    /**
     * Update an existing entity into repository
     * @param $entity Entity|Entity[]
     * @return int - Number of affected registers
     */
    public abstract function update($entity) : int;

    /**
     * Save the entity in the repository. If the entity already exists it makes an Repository::update, otherwise
     * it Repository::insert the $entity
     * @param Entity|Entity[] $entity
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