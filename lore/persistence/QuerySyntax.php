<?php
namespace lore\persistence;


abstract class QuerySyntax
{

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
     * Add an query filter to the filter queue
     * @param QueryFilter $filter
     */
    function addFilter(QueryFilter $filter){
        $this->lastFilter = $filter;
    }

    /**
     * Return an single result from the query
     * @return Entity
     */
    public abstract function one();

    /**
     * Return all results from the query
     * @return Entity[]
     */
    public abstract function all();

    /**
     * Add a filter in the query
     * @param $field
     * @return QueryFilter
     */
    public function where($field) : QueryFilter{
        $this->firstFilter = new QueryFilter($this, $field);
        $this->lastFilter = $this->firstFilter;
    }

}