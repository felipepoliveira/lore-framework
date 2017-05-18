<?php
namespace lore\web;

use lore\ApplicationContext;

/**
 * Stores information about the request made by the user. An instance of this object can be accessed inside the
 * Lore::app()->getRequest() method
 * Class Request
 * @package lore
 */
class Request
{
    public const    GET =       1 << 0,
                    POST =      1 << 1,
                    PUT =       1 << 2,
                    DELETE =    1 << 3;

    /**
     * Store the relative requested uri sent by the client
     * @var string
     */
    private $requestedUri;

    /**
     * Store the http method in the request sent by the client
     * @var integer
     */
    private $method;

    /**
     * Request constructor.
     * @param $appContext ApplicationContext - The application context
     */
    function __construct($appContext)
    {
        $this->detectRequestMethod();
        $this->extractRelativeRequestedUri($appContext);
    }

    /**
     * Return the http request method sent by the client
     * @see Request static (GET, POST, PUT, DELETE)
     * @return int
     */
    public function getMethod(): int
    {
        return $this->method;
    }

    /**
     * Get the relative uri based on DOCUMENT_ROOT
     * @return string
     */
    public function getRawRequestedUri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     *Get the relative uri based on project folder
     * @return string
     */
    public function getRequestedUri(): string
    {
        return $this->requestedUri;
    }

    /**
     * Return the requested data depending on resquest method. If the request method is post or put the request data
     * is returned ver the $_POST array, otherwise is returned from $_GET array
     * @return array
     */
    public function requestData(){
        return ($this->isPost() || $this->isPut())? $_POST : $_GET;
    }

    /**
     * Return the data sent by the client in request in an formatted array
     * @return array
     */
    public function requestDataAsRecursiveArray(){
        $array = array();

        //Iterate over
        foreach ($this->requestData() as $key => $value) {
            $this->lastRecursiveIterationOf($array, $key, $trueKey, $value);
        }

        return $array;
    }

    protected function lastRecursiveIterationOf(&$array, $key, &$trueKey, $value){
        //Check if the key has an composition value (separting . or _ )
        $pos = strpos($key, ".");
        if(!$pos) $pos = strpos($key, "_");

        if($pos){
            //Get the key before the first dot
            $keyBeforeDot = substr($key, 0, $pos);
            //Get the key after the first dot
            $trueKey = substr($key, $pos+1, strlen($key));

            //If the index is not defined craete an array
            if(!isset($array[$keyBeforeDot])){
                $array[$keyBeforeDot] = [];
            }

            //Iterate over the last index while the key has a dot
            $this->lastRecursiveIterationOf($array[$keyBeforeDot], $trueKey, $trueKey, $value);
        }else{
            //If the key does not have a dot put the value into array
            $trueKey = $key;
            $array[$trueKey] = $value;
        }
    }

    /**
     * Return an flag if the request is an specific $method
     * @param string|int $method - When string: The method name in lowercase, when int: the method name based on
     * Request:: constants
     * @return bool
     */
    public function is($method){
        if(is_string($method)){
            switch (trim($method)) {
                case "get":
                    return $this->isGet();
                case "post":
                    return $this->isPost();
                case "put":
                    return $this->isPost();
                case "delete":
                    return $this->isDelete();
                default:
                    return false;
            }
        }else if(is_int($method)){
            return $this->method === $method;
        }else{
            return false;
        }
    }

    /**
     * Return if the request method is a GET method
     * @return bool
     */
    public function isGet()
    {
        return $this->method === Request::GET;
    }

    /**
     * Return if the request method is a POST method
     * @return bool
     */
    public function isPost()
    {
        return $this->method === Request::POST;
    }

    /**
     * Return if the request method is a PUT method
     * @return bool
     */
    public function isPut()
    {
        return $this->method === Request::PUT;
    }

    /**
     * Return if the request method is a DELETE method
     * @return bool
     */
    public function isDelete()
    {
        return $this->method === Request::DELETE;
    }

    /**
     * Extract the relative path of the requested uri
     * @param $appContext ApplicationContext
     */
    protected function extractRelativeRequestedUri($appContext){
        $this->requestedUri = substr(   $this->getRawRequestedUri(),
                                        strlen($appContext->getRelativePath()),
                                        strlen($this->getRawRequestedUri()));
    }

    /**
     * Detect the request method
     */
    protected function detectRequestMethod(){
        switch ($_SERVER["REQUEST_METHOD"]){
            case "GET":
                $this->method = Request::GET;
                break;
            case "POST":
                $this->method = Request::POST;
                break;
            case "PUT":
                $this->method = Request::PUT;
                break;
            case "DELETE":
                $this->method = Request::DELETE;
                break;
            default:
                $this->method = Request::GET;
                break;
        }
    }
}