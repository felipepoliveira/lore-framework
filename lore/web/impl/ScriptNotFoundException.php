<?php
namespace lore\web;


use Throwable;

/**
 * Class ScriptNotFoundException - Exception to be thrown when an requested Script does not exists in
 * the server
 * @package lore\web
 */
class ScriptNotFoundException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}