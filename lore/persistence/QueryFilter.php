<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 18/09/2017
 * Time: 15:30
 */

namespace lore\persistence;


class QueryFilter
{
    public const    FILTER_EQUALS = 0,
                    FILTER_DIFFERENT = 1,

                    FILTER_LESS_THAN = 100,
                    FILTER_LESS_OR_EQUALS_THAN = 101,

                    FILTER_GREATER_THAN = 200,
                    FILTER_GREATER_OR_EQUALS_THAN = 201,

                    FILTER_STARTS_WITH = 300,
                    FILTER_ENDS_WITH = 301,
                    FILTER_CONTAINS = 302;

    public const    BIND_AND = 1,
                    BIND_OR = 2,
                    BIND_XOR = 3;

    /**
     * Constants of QueryFilter::BIND_*
     * @var int
     */
    private $bindType;

    /**
     * The filtered field
     * @var mixed
     */
    private $field;

    /**
     * The next filter connected with $this
     * @var QueryFilter
     */
    private $nextFilter = null;

    /**
     * The query syntax that of this filter
     * @var Repository
     */
    private $querySyntax = null;

    /**
     * The compared value
     * @var mixed
     */
    private $value;


    /**
     * Constants of QueryFilter::FILTER_*
     * @var int
     */
    private $filterType;

    function __construct(QuerySyntax $querySyntax, $field)
    {
        $this->querySyntax = $querySyntax;
        $this->field = $field;
    }

    /**
     * Set the next filter of this filter;
     * Add the filter in the query syntax ($this->querySyntax) filter queue
     * @param $field mixed
     */
    protected function setNextFilter($field){
        $newFilter = new QueryFilter($this->querySyntax, $field);
        $this->querySyntax->addFilter($newFilter);
        $this->nextFilter = $newFilter;
    }

    /**
     * Get the next filter
     * @return QueryFilter
     */
    public function getNextFilter(): QueryFilter
    {
        return $this->nextFilter;
    }

    public function and($field){
        $this->bindType = self::BIND_AND;
        $this->setNextFilter($field);


        return $this->nextFilter;
    }

    public function or($field){
        $this->bindType = self::BIND_OR;
        $this->setNextFilter($field);

        return $this->nextFilter;
    }

    public function differentThan($value){
        $this->filterType = self::FILTER_DIFFERENT;
    }

    public function equalTo($value){
        $this->filterType = self::FILTER_EQUALS;
        $this->value = $value;

        return $this;
    }

    public function greaterThan($value){
        $this->filterType = self::FILTER_LESS_THAN;
        $this->value = $value;

        return $this;
    }

    public function greaterOrEqualsThan($value){
        $this->filterType = self::FILTER_LESS_OR_EQUALS_THAN;
        $this->value = $value;

        return $this;
    }

    public function lessThan($value){
        $this->filterType = self::FILTER_LESS_THAN;
        $this->value = $value;

        return $this;
    }

    public function lessOrEqualsThan($value){
        $this->filterType = self::FILTER_LESS_OR_EQUALS_THAN;
        $this->value = $value;

        return $this;
    }

    public function startsWith($value){
        $this->filterType = self::FILTER_STARTS_WITH;
        $this->value = $value;

        return $this;
    }

    public function endsWith($value){
        $this->filterType = self::FILTER_ENDS_WITH;
        $this->value = $value;

        return $this;
    }

    public function contains($value){
        $this->filterType = self::FILTER_CONTAINS;
        $this->value = $value;

        return $this;
    }

    public function one(){
        return $this->querySyntax->one();
    }

    public function all(){
        return $this->querySyntax->all();
    }
}