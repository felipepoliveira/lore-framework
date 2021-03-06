<?php
namespace lore\web;
use lore\Lore;

/**
 * Represent an route rule that is defined in the configuration file: project => router => rules.
 * The rules can be:
 * ------------------------------------------------
 * Prefixed: An valid uri that ends with '/*'
 * uri: api/*
 * use: "rest/*" => "app/controllers/rest/*"
 * ===
 * Example 1
 * configured uri: "rest/v1/*" => "app/controllers/rest/v1/*" (all uri that starts with "rest/v1" will be handled by
 * any script in "app/controllers/rest/v1" folder
 * requested uri: "rest/v1/user"
 * produces the uri: user/
 *===
 * Example 2
 * configured uri: "adm/*" => "app/controllers/view/AdminController.php" (all uri that starts with "adm/*" will be
 * handled specifically by the script "app/controllers/view/AdminController.php"
 * requested uri: "adm/user/delete/1"
 * produces the uri: "user/delete/1"
 *
 * @package lore\web
 */
class RouteRule
{

    /*
     * The prefix tag
     */
    protected const PREFIX_TAG = "/*";
    protected const SUFFIX_TAG = "*/";

    protected const TYPE_SPECIFIC = 0, TYPE_PREFIXED = 1, TYPE_SUFFIXED  = 2, TYPE_KEY_VALUE = 3;

    /**
     * The raw uri requested by the client
     * @var string
     */
    private $routeRule;

    /**
     * Scripts that will handle the request
     * @var array
     */
    private $scripts = [];

    /**
     * Enum (check RouteRule::TYPE_* constants) of types of route rules.
     * The supported types of route rules is:
     * SPECIFIC = 0
     * PREFIXED = 1
     * @var int
     */
    private $type;


    /**
     * Create an instance of a RouteRule object.
     * When an object of this class is instantiated the uri is interpreted to validated and identify the given
     * rule.
     * @param $uri
     * @param $scripts
     */
    function __construct($uri, $scripts)
    {
        $this->digestUri($uri, $scripts);
    }

    /**
     * @return string
     */
    public function getRouteRule(): string
    {
        return $this->routeRule;
    }

    /**
     * @return array
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Remove the PREFIX_TAG and SUFFIX_TAG from the route rule uri
     */
    protected function digestKeyValueUri(){
        $this->digestPrefixedUri();
        $this->digestSuffixedUri();
    }

    /**
     * Remove the PREFIX_TAG from the route rule uri
     */
    protected function digestPrefixedUri(){
        $this->routeRule = substr($this->routeRule, 0 , strlen($this->routeRule) - strlen(self::PREFIX_TAG));
    }

    /**
     * Remove the SUFFIX_TAG from the route rule uri
     */
    protected function digestSuffixedUri(){
        $this->routeRule = substr($this->routeRule, strlen(self::SUFFIX_TAG) , strlen($this->routeRule));
    }

    /**
     * Digest the uri to identify witch type of route rule it is
     * @param $uri
     * @param $scripts
     * @throws \Exception
     */
    protected function digestUri($uri, $scripts){
        //Store the request of the client
        $this->scripts = $scripts;
        $this->routeRule = $uri;

        //Check type of rule and digest it
        if(self::isKeyValue($uri)){
            $this->digestKeyValueUri();
            $this->type = self::TYPE_KEY_VALUE;
        }else if(self::isPrefixed($uri)){
            $this->digestPrefixedUri();
            $this->type = self::TYPE_PREFIXED;
        }else if(self::isSuffixed($uri)){
            $this->digestSuffixedUri();
            $this->type = self::TYPE_SUFFIXED;
        }else{
            $this->type = self::TYPE_SPECIFIC;
        }
    }

    /**
     * Return flag indicating if the given uri is an key value type.
     * Example of valid prefixed uri: "* /uri/*"
     * @param $uri
     * @return bool
     */
    public static function isKeyValue($uri){
        return (self::isPrefixed($uri) && self::isSuffixed($uri));
    }

    /**
     * Return flag indicating if the given uri is an prefixed type.
     * Example of valid prefixed uri: "/uri/*"
     * @param $uri
     * @return bool
     */
    public static function isPrefixed($uri){
        return strrpos($uri, self::PREFIX_TAG) === (strlen($uri) - strlen(self::PREFIX_TAG));
    }

    /**
     * Return flag indicating if the given uri is suffixed type
     * Example of valid suffixed uri: "* /uri/"
     * @param $uri
     * @return bool
     */
    public static function isSuffixed($uri){
        return strrpos($uri, self::SUFFIX_TAG) === 0;
    }

    /**
     * Flag indicating if the request of the client match this rule
     * @param Request $request
     * @return bool
     */
    public function match(Request $request){
        switch ($this->type){
            case self::TYPE_KEY_VALUE:
                return $this->matchKeyValue($request);
            case self::TYPE_PREFIXED:
                return $this->matchPrefixed($request);
            case self::TYPE_SPECIFIC:
                return $this->matchSpecific($request);
            case self::TYPE_SUFFIXED:
                return $this->matchSuffixed($request);
            default:
                return false;
        }
    }

    /**
     * Return an flag indicating if the rule match in key value type
     * @param Request $request
     * @return bool
     */
    protected function matchKeyValue(Request $request){
        return strpos($request->getRequestedUri(), "/" . $this->routeRule) !== false;
    }

    /**
     * Return an flag indicating if the rule match in prefixed uri type
     * @param Request $request
     * @return bool
     */
    protected function matchPrefixed(Request $request){
        $routeRuleLength = strlen($this->routeRule);
        return substr($request->getRequestedUri(), 0, $routeRuleLength) == $this->routeRule;
    }

    /**
     * @param Request $request
     * @return bool - Flag indicating if the request match the rule as 'specific type'
     */
    protected function matchSpecific(Request $request){
        return $request->getRequestedUri() === $this->routeRule;
    }

    /**
     * @param Request $request
     * @return bool - Flag indicating if the request match the rule as 'suffixed type'
     */
    protected function matchSuffixed(Request $request){
        return  strrpos($request->getRequestedUri(), $this->routeRule) ===
                strlen($request->getRequestedUri()) - strlen($this->routeRule);
    }

    /**
     * Produce the uri from the request formatting it to be handled
     * @param Request $request
     * @return bool|string
     */
    public function produceUri(Request $request){
        switch ($this->type){
            case self::TYPE_KEY_VALUE:
                return $this->produceKeyValueUri($request);
            case self::TYPE_PREFIXED:
                return $this->producePrefixedUri($request);
            case self::TYPE_SPECIFIC:
                return $request->getRequestedUri();
            case self::TYPE_SUFFIXED:
                return $this->produceSuffixedUri($request);
            default:
                return false;
        }
    }

    /**
     * Produce an key value uri removing the prefix and the suffix of the requested uri from Request.
     * Example: if the Request::getRequestedUri is "/rest/test/a" and the route rule is "* /test/*" it
     * produces the uri: "/rest/a" removing the prefix
     * @param Request $request
     * @return string
     */
    protected function produceKeyValueUri(Request $request) : string {
        //Let's go. Take this uri as example: '/code/test/dojo' where the rule is: */test/*

        //First with take the left part (the '/code')
        $digestedUri =  substr($request->getRequestedUri(), 0,
                        strpos($request->getRequestedUri(), "/" . $this->routeRule));

        //After that will check if the uri contains the right part 'test/'
        $pos = strpos($request->getRequestedUri(), $this->routeRule . "/");
        if($pos){

            //If it has, advance the position going AFTER the key value
            //so if the key value is : test, skip: T-E-S-T and +1 for the '/'
            $pos += strlen($this->routeRule) + 1;

             //Concat the uri if the right part /code/dojo
            $digestedUri .= "/" . substr($request->getRequestedUri(), $pos, strlen($request->getRequestedUri()));
        }

        return $digestedUri;
    }

    /**
     * Produce an prefixed uri removing the prefix of the requested uri from Request.
     * Example: if the Request::getRequestedUri is "/rest/test" and the route rule is "/rest/*" it
     * produces the uri: "/test" removing the prefix
     * @param Request $request
     * @return string
     */
    protected function producePrefixedUri(Request $request) : string {
        return substr($request->getRequestedUri(), strlen($this->routeRule), strlen($request->getRequestedUri()));
    }

    /**
     * Produce an suffixed uri removing the sufix of the requested uri from Request.
     * Example: if the Request::getRequestedUri is "/rest/test" and the route rule is "* /test" it
     * produces the uri: "/rest" removing the prefix
     * @param Request $request
     * @return string
     */
    protected function produceSuffixedUri(Request $request) : string{
        return substr(  $request->getRequestedUri(),
                        0,
                        strlen($request->getRequestedUri()) - strlen($this->routeRule) - 1);
    }
}