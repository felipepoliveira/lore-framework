<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 08/05/2017
 * Time: 10:35
 */

namespace lore;


class ApplicationContext
{
    private $relativePath;

    function __construct()
    {
        $absolutePath = $this->getAbsolutePath();
        $this->relativePath = substr($absolutePath, strlen($_SERVER["DOCUMENT_ROOT"]), strlen($absolutePath));
        $this->relativePath = str_replace(DIRECTORY_SEPARATOR, "/", $this->relativePath);
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * @return string
     */
    public function getAbsolutePath() : string {
        return dirname(dirname(__DIR__));
    }
}