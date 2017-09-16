<?php
namespace lore;

/**
 * Class ApplicationContext - Class that store data about the application runtime environment.
 * An object of this class should be accessed in the singleton in Lore::app()->getContext()
 * @package lore
 */
class ApplicationContext
{
    /**
     * Store the absolute path (to server document root)
     * @var string
     */
    private $absolutePath;

    /**
     * Store the relative path (to server document root)
     * @var string
     */
    private $relativePath;

    function __construct()
    {
        $this->absolutePath = dirname(dirname(__DIR__));
        $this->relativePath = substr($this->absolutePath, strlen($_SERVER["DOCUMENT_ROOT"]), strlen($this->absolutePath));
        $this->relativePath = str_replace(DIRECTORY_SEPARATOR, "/", $this->relativePath);
    }

    /**
     * Return the relative path (relative to server document root). This information is loaded in the __construct method
     * of this class
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * Return the absolute path (relative to server document root) that this application is running
     * @return string
     */
    public function getAbsolutePath() : string {
        return $this->absolutePath;
    }
}