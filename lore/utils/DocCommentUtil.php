<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 10/05/2017
 * Time: 18:35
 */

namespace lore\util;


abstract class DocCommentUtil
{
    /**
     * @param string $doc
     * @param string $tag
     * @return string
     */
    public static function readAnnotation($doc, $tag){
        preg_match_all("#@$tag(.*?)\n#s", $doc, $annotations);
        return (isset($annotations[1][0]))? trim($annotations[1][0]) : false;
    }
}