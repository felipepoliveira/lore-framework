<?php
namespace lore;

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
     * @var string
     */
    private $requestedUri;

    /**
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
     * @return int
     */
    public function getMethod(): int
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRawRequestedUri(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     * @return string
     */
    public function getRequestedUri(): string
    {
        return $this->requestedUri;
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