<?php
namespace lore\util;

use lore\Lore;

abstract class File
{
    /**
     * Return the last modification date in an file
     * @param $file - The file that will be checked
     * @return int|bool - The last modification time or false if the file does not exists
     */
    public static function lastModified($file){
        return file_exists($file) ? filemtime($file) : false;
    }

    public static function exists($file){
        return file_exists($file);
    }

    /**
     * Check if an file exists in given directories
     * @param $file string
     * @param $dirs array
     * @param bool $relative - Define if the $file is relative or not
     * @return string|false
     */
    public static function checkFileInDirectories($file, $dirs, $relative = true){
        foreach ($dirs as $dir){
            //Put full path into the view file
            $fullName = ($relative) ? Lore::app()->getContext()->getAbsolutePath() .  "/$dir/$file" : "$dir/$file";

            if(file_exists($fullName)){
                return $fullName;
                break;
            }
        }
        return false;
    }
}