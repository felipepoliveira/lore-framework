<?php
namespace lore\web;

require_once "ResponseCache.php";

use lore\Configurations;

/**
 * Store data to the response that will be send to the client
 * Class Response
 * @package lore
 */
class Response
{
    /**
     * The http response code
     * @var integer
     */
    private $code;

    /**
     * The errors founded in the server request processing to be sent to client feedback
     * @var string[]
     */
    private $errors;

    /**
     * The data that will be sent to the client
     * @var mixed
     */
    private $data = null;

    /**
     * The uri that store the resource that will be sent to the client
     * @var string
     */
    private $uri = null;

    /**
     * Flag indicating if the request will be redirect to another uri
     * @var bool
     */
    private $redirect = false;

    /**
     * The charset encoding of the response
     * @var string
     */
    private $charset;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * An flag indicating if a resource (file like image, pdf, etc.) will be sent to the client
     * @var bool
     */
    private $sendResource;

    /**
     * @var ResponseCache
     */
    private $cache;

    /**
     * The http response code
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Response constructor.
     * @param string $uri
     * @param bool $redirect
     * @param int $code
     * @param $contentType - The content type that will be sent to the client
     * @param $charset - The charset encoding of the response
     * @param $sendResource - Flag indicating if the response will sent a resource
     */
    function __construct($uri = null, $redirect = false, $code = 200, $contentType = "text/html", $charset = null,
                         $sendResource = false)
    {
        $this->uri = $uri;
        $this->redirect = $redirect;
        $this->code = $code;
        $this->headers["Content-Type"] = $contentType;
        $this->charset = $charset ?? Configurations::get("project", "response")["defaultCharset"];
        $this->sendResource = $sendResource;
        $this->cache = new ResponseCache();
    }

    /**
     * Defines the http response code that will be sent to the client
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * Return the errors found in the server request processing to be sent to the client for feedback
     * @return \string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Defines the errors that will be sent to the client for feedback
     * @param \string[] $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Return the data that will be sent to the client
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Defines the data that will be sent to the client
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Defines if the request will be redirect to another uri
     * @param bool $redirect
     */
    public function setRedirect(bool $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Return an flag inficating if the request will be redirect to another uri
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    /**
     * Return the uri that has the resource to be sent to the client
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Define the uri with the resource that will be sent to the client
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Return the charset encoding of the response
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Define the charset encoding of the response
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Return the content type of the response
     * @return string|null
     */
    public function getContentType()
    {
        return $this->headers["Content-Type"];
    }

    /**
     * Define the content type of the response
     * @param string $contentType
     */
    public function setContentType(string $contentType)
    {
        $this->headers["Content-Type"] = $contentType;
    }

    /**
     * Flag indicating if the response is sending an resource like media, documents, etc.
     * @return bool
     */
    public function isSendingResource(): bool
    {
        return $this->sendResource;
    }

    /**
     * Define if the response is sending an resource like media, documents, etc.
     * @param bool $sendResource
     */
    public function setSendResource(bool $sendResource)
    {
        $this->sendResource = $sendResource;
    }

    /**
     * Store an header value in the response
     * @param $key
     * @param $value
     */
    public function putHeader(string $key, string $value){
        $this->headers[$key] = $value;
    }

    /**
     * Get an stored header value
     * @param string $key
     * @return string
     */
    public function getHeader(string $key){
        return $this->headers[$key];
    }

    /**
     * Return the headers map of the response
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * @return ResponseCache
     */
    public function getCache(): ResponseCache
    {
        return $this->cache;
    }

    public function hasErrors(){
        return $this->errors !== null && count($this->errors) > 0;
    }
}