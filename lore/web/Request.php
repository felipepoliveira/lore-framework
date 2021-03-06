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
     * Store the headers from the client request
     * @var string[]
     */
    private $headers;

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
        $this->detectContentType();
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
     * @param string $requestedUri
     */
    function setRequestedUri(string $requestedUri)
    {
        $this->requestedUri = $requestedUri;
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
     * Return all headers sent by the client in the request
     * @return \string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get an specific request header by its name. Return false if the headers was not sent by the client
     * @param $header
     * @return bool|\string[]
     */
    public function getHeader($header){
        return $this->headers[$header] ?? false;
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

    protected function detectContentType(){
        $this->headers = getallheaders();
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