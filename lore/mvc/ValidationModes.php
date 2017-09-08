<?php
/**
 * Created by PhpStorm.
 * Usuario: Felipe
 * Date: 16/05/2017
 * Time: 10:48
 */

namespace lore\mvc;


abstract class ValidationModes
{
    public const    ALL     = 0,
                    ONLY    = 1,
                    EXCEPT  = 2;
}