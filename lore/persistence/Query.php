<?php
namespace lore\persistence;

require_once "QueryFilter.php";

/**
 * Linked based class to make queries in repository.
 * This is a abstraction and must be implemented in the Repository implementation works
 * Class Query
 * @see Repository
 * @package lore\persistence
 */
abstract class Query
{
    //The fetch modes
    const   FETCH_ALL = 1, //Query all fields of the entity
            FETCH_ONLY = 2, //Query only the given fields of the entity
            FETCH_EXCEPT = 3; //Query all except the given fields of the entity


    /**
     * @var EntityMetadata
     */
    private $metadata;

    /**
     * The first filter of the filter queue
     * @var QueryFilter
     */
    private $firstFilter;

    /**
     * The last filter of the filter queue
     * @var QueryFilter
     */
    private $lastFilter;

    /**
     * The query mode
     * Query::MODE_ONLY
     * @var int
     */
    protected $fetchMode;

    /**
     * The fields to fetch in query
     * @var array
     */
    protected $fetchFields;

    /**
     * Query constructor.
     * @param EntityMetadata $metadata
     */
    function __construct(EntityMetadata $metadata)
    {
        $this->metadata = $metadata;

        //Initialize the fetch options
        $this->fetchMode = Query::FETCH_ALL;
        $this->fetchFields = [];
    }


    /**
     * Add an query filter to the filter queue
     * @param QueryFilter $filter
     */
    function addFilter(QueryFilter $filter){
        $this->lastFilter = $filter;
    }

    /**
     * Return the number of occurrences of a query produced by the Query::all() method
     * @return int
     */
    public function count() : int{
        return count($this->all());
    }

    /**
     * Return an flag indicating if the query bring any result
     * @return bool
     */
    public function exists(){
        return $this->count() > 0;
    }

    /**
     * Return an single result from the query
     * @return Entity|false
     */
    public abstract function one();

    /**
     * Return all results from the query
     * @return Entity[]
     */
    public abstract function all() : array;

    /**
     * Add a filter in the query
     * @param $field
     * @return QueryFilter
     */
    public function where($field) : QueryFilter{
        $this->firstFilter = new QueryFilter($this, $field);
        $this->lastFilter = $this->firstFilter;

        return $this->lastFilter;
    }

    /**
     * @return array
     */
    public function getFetchFields()
    {
        return $this->fetchFields;
    }

    /**
     * @return int
     */
    public function getFetchMode()
    {
        return $this->fetchMode;
    }

    /**
     * Set the fetch mode and fetch fields
     * @param $fetchMode
     * @param $fetchFields
     * @return $this
     */
    public function fields($fetchMode, $fetchFields = []){
        $this->fetchMode = $fetchMode;
        $this->fetchFields = $fetchFields;

        return $this;
    }

    /**
     * Return the first filter of the filter queue
     * @return QueryFilter
     */
    public function getFilter()
    {
        return $this->firstFilter;
    }

    /**
     * Return the last fielter of the filter queue
     * @return QueryFilter
     */
    public function getLastFilter()
    {
        return $this->lastFilter;
    }

    /**
     * Check if the query has an filter
     * @return bool
     */
    public function hasFilter() : bool {
        return $this->firstFilter !== null;
    }

    /**
     * @return EntityMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}