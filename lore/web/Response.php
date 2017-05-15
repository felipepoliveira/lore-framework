<?php
namespace lore\web;

/**
 * Store data to the response that will be send to the client
 * Class Response
 * @package lore
 */
class Response
{
    /**
     * @var integer
     */
    private $code;

    /**
     * @var string[]
     */
    private $errors;

    /**
     * @var mixed
     */
    private $data = null;

    /**
     * @var string
     */
    private $uri = null;

    /**
     * @var bool
     */
    private $redirect = false;

    /**
     * Response constructor.
     * @param string $uri
     * @param bool $redirect
     * @param int $code
     */
    function __construct($uri = null, $redirect = false, $code = 200)
    {
        $this->uri = $uri;
        $this->redirect = $redirect;
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return \string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param \string[] $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param bool $redirect
     */
    public function setRedirect(bool $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->redirect;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}