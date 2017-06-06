<?php

use lore\mvc\Model;

/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 06/06/2017
 * Time: 08:21
 */
class Test extends Model
{
    /**
     * @var int
     * @min 1
     */
    private $a;

    /**
     * @var string
     * @min 1
     */
    private $b;
}