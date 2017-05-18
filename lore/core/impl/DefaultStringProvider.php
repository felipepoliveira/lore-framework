<?php
namespace lore;

use lore\util\File;

require_once __DIR__ . "/../StringProvider.php";

class DefaultStringProvider extends StringProvider
{
    /**
     * @var array
     */
    private $strings = [];

    public function loadStrings($file)
    {
        //Get the file in string paths
        $filePath = File::checkFileInDirectories($file, $this->stringsDirectories);
        if($filePath){
            $this->strings = array_merge($this->strings, require "$filePath");
        }
    }

    public function getString($msgCode, $defaultMsg = null)
    {
        if(isset($this->strings[$msgCode])){
            return $this->strings[$msgCode];
        }else if(isset($defaultMsg)){
            return $defaultMsg;
        }else{
            return false;
        }
    }

    public  function hasString($msgCode): bool
    {
        return isset($this->strings[$msgCode]);
    }

}