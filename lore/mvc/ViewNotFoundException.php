<?php
namespace lore\mvc;

use Throwable;

/**
 * Class ViewNotFoundException - Exception to be thrown when the Controller request an view
 * that does not exists
 * @package lore\mvc
 */
class ViewNotFoundException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}