<?php
/**
 * Created by PhpStorm.
 * Usuario: Felipe
 * Date: 31/05/2017
 * Time: 10:13
 */

namespace lore\mvc;

use Exception;

/** Exception to be thrown when an ModelLoader is not defined.
 * Class NoModelLoaderDefinedException
 * @package lore\mvc
 */
class NoModelLoaderDefinedException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}