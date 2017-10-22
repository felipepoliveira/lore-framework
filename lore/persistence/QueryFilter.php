<?php
namespace lore\persistence;

/**
 * Stores all filters used in the query.
 * Objects of this class is created automatically in the Query object.
 * Class QueryFilter
 * @package lore\persistence
 */
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
    private $bindType = false;

    /**
     * The filtered field
     * @var string
     */
    private $field;

    /**
     * The next filter connected with $this
     * @var QueryFilter
     */
    private $nextFilter = null;

    /**
     * The query syntax that of this filter
     * @var Query
     */
    private $query = null;

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

    function __construct(Query $querySyntax, $propName)
    {
        $this->query = $querySyntax;

        //Search fot the field passing the property name and check if it exists...9
//        $field = $this->query->getMetadata()->findFieldByPropertyName($propName);
//        if($field === false){
//            throw new PersistenceException("The property: \"" . $this->query->getMetadata()->getEntityClassName() .
//                        "::$propName\" was not found in query");
//        }
        //Put the field name in the query filter
        //$this->field = $field->getName();
        $this->field = $this->query->getMetadata()->getEntityName() . "." . $propName;
    }

    /**
     * Set the next filter of this filter;
     * Add the filter in the query syntax ($this->querySyntax) filter queue
     * @param $field mixed
     */
    protected function setNextFilter($field){
        $newFilter = new QueryFilter($this->query, $field);
        $this->query->addFilter($newFilter);
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

    /**
     * Return the type of the field.
     * The values of the filter type is defined by the QueryFilter::FILTER_* constants
     * @return int
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * Return the connection type with the filter with the next one.
     * If the this QueryFilter does not have an next filter this method returns false
     * @return int
     */
    public function getBindType()
    {
        return $this->bindType;
    }

    /**
     * Return the name of the filtrated field.
     * This value is defined automatically when an instance of this class is created. The name
     * will be the '@'field name of the given property name
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Return the value used in the filter
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return an flag indicating if this QueryFilter is a string match filter:
     * QueryFilter::FILTER_STARTS_WITH
     * QueryFilter::FILTER_ENDS_WITH
     * QueryFilter::FILTER_CONTAINS
     * @return bool
     */
    public function isMatchFilterType(){
        return  $this->filterType == self::FILTER_STARTS_WITH ||
                $this->filterType == self::FILTER_ENDS_WITH ||
                $this->filterType == self::FILTER_CONTAINS;
    }
    /**
     * Return an flag indicating if the filter has another filter
     * @return bool
     */
    public function hasNextFilter() : bool {
        return $this->nextFilter !== null;
    }

    /**
     * Bind this filter with another one connecting them with QueryFilter::BIND_AND
     * @param $prop - The filtrated property
     * @return QueryFilter - The next created filter
     */
    public function and($prop){
        $this->bindType = self::BIND_AND;
        $this->setNextFilter($prop);


        return $this->nextFilter;
    }

    /**
     * Bind this filter with another one connecting them with QueryFilter::BIND_OR
     * @param $prop - The filtrated property
     * @return QueryFilter - The next created filter
     */
    public function or($prop){
        $this->bindType = self::BIND_OR;
        $this->setNextFilter($prop);

        return $this->nextFilter;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_DIFFERENT type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function notEquals($value){
        $this->filterType = self::FILTER_DIFFERENT;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_EQUALS type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function equals($value){
        $this->filterType = self::FILTER_EQUALS;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_GREATER_THAN type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function greaterThan($value){
        $this->filterType = self::FILTER_GREATER_THAN;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_GREATER_OR_EQUALS_THAN type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function greaterOrEqualsThan($value){
        $this->filterType = self::FILTER_GREATER_OR_EQUALS_THAN;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_LESS_THAN type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function lessThan($value){
        $this->filterType = self::FILTER_LESS_THAN;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_LESS_OR_EQUALS_THAN type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function lessOrEqualsThan($value){
        $this->filterType = self::FILTER_LESS_OR_EQUALS_THAN;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_STARTS_WITH type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function startsWith($value){
        $this->filterType = self::FILTER_STARTS_WITH;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_ENDS_WITH type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function endsWith($value){
        $this->filterType = self::FILTER_ENDS_WITH;
        $this->value = $value;

        return $this;
    }

    /**
     * Define the type of this filter as an QueryFilter::FILTER_CONTAINS type and stores the value
     * of the filter. Returns $this to make an link of methods
     * @param $value - The value of the filter
     * @return $this - $this object to make links
     */
    public function contains($value){
        $this->filterType = self::FILTER_CONTAINS;
        $this->value = $value;

        return $this;
    }

    /**
     * Trigger the query in repository and returns the Entity found in Repository.
     * @return Entity|false
     */
    public function one(){
        return $this->query->one();
    }

    /**
     * Trigger the query in repository and returns an array with the Entity found in Repository
     * @return array|Entity[]
     */
    public function all(){
        return $this->query->all();
    }

    /**
     * Return the quantity of fetched results in the QueryFilter::all()
     * @return int
     */
    public function count(){
        return $this->query->count();
    }

    public function exists(){
        return $this->query->exists();
    }
}