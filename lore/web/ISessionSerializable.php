<?php
/**
 * Created by PhpStorm.
 * User: Felipe Oliveira
 * Date: 01/10/2017
 * Time: 09:54
 */

namespace lore\web;


interface ISessionSerializable
{
    function serializeToSession();
}