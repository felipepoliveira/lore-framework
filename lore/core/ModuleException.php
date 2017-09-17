<?php
namespace lore;

use Throwable;

/**
 * Class ModuleEception - Used when an certain module of the application has some problem (like not loaded yet,
 * not implemented, etc.).
 * @package lore
 */
class ModuleException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}