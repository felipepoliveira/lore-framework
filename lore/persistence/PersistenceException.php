<?php
namespace lore\persistence;

use Throwable;

/**
 * Exception trigger when errors in persistence module occurs
 * Class PersistenceException
 * @package lore\persistence
 */
class PersistenceException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}