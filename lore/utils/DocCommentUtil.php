<?php

namespace lore\util;


abstract class DocCommentUtil
{
    /**
     * Read an annotation value from an given doc comment
     * @param string $doc
     * @param string $tag
     * @return string
     */
    public static function readAnnotationValue(string $doc, string $tag){
        preg_match_all("#@$tag(.*?)\n#s", $doc, $annotations);
        return (isset($annotations[1][0]))? trim($annotations[1][0]) : false;
    }

    /**
     * Check if an annotation exists an an given doc comment
     * @param string $doc
     * @param string $value
     * @return bool
     */
    public static function annotationExists(string $doc, string $value){
        return strrpos($doc, "@$value") !== false;
    }
}