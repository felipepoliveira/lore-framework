<?php
namespace lore;

use Exception;

/**
 * Exception thrown when an configuration file or a configuration inside an configuration is not found
 * Class ConfigurationNotFoundException
 * @package lore
 */
class ConfigurationException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}