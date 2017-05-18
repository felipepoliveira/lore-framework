<?php
namespace lore;

/**
 * Class StringProvider - Abstract class for message handling
 * @package lore
 */
abstract class StringProvider
{
    /**
     * @var array
     */
    protected $stringsDirectories;

    function __construct()
    {
        $this->stringsDirectories = Configurations::get("project", "stringProvider")["dirs"];
    }

    /**
     * Get the string directory
     * @return array
     */
    public function getStringsDirectories(): array
    {
        return $this->stringsDirectories;
    }

    /**
     * Load an string provider file
     * @param $file string
     * @return void
     */
    public abstract function loadStrings($file);

    /**
     * Get an message from an message file
     * @param string $msgCode
     * @param string $defaultMsg
     * @return string|null
     */
    public abstract function getString($msgCode, $defaultMsg = null);

    /**
     * Return an flag indicating if the string provider has an specific string
     * @param $msgCode
     * @return bool
     */
    public abstract function hasString($msgCode) : bool;
}