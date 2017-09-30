<?php
namespace lore;

/**
 * Class ApplicationContext - Class that store data about the application runtime environment.
 * An object of this class should be accessed in the singleton in Lore::app()->getContext()
 * @package lore
 */
class ApplicationContext
{
    public const    STATE_PRODUCTION = 1,
                    STATE_DEVELOPMENT = 2;

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

    /**
     * @var int
     */
    private $applicationState;

    function __construct()
    {
        $this->absolutePath = dirname(dirname(__DIR__));
        $this->relativePath = substr($this->absolutePath, strlen($_SERVER["DOCUMENT_ROOT"]), strlen($this->absolutePath));
        $this->relativePath = str_replace(DIRECTORY_SEPARATOR, "/", $this->relativePath);
    }

    /**
     * Load the application state from the configuration file project => application => state
     */
    function loadApplicationState(){
        if( Configurations::contains("project", "application") &&
            isset(Configurations::get("project", "application")["state"])){

            $state = Configurations::get("project", "application")["state"];

            switch (strtolower($state)){
                case "development":
                    $this->applicationState = self::STATE_DEVELOPMENT;
                    break;
                case "production":
                    $this->applicationState = self::STATE_PRODUCTION;
                    break;
                default:
                    throw new ConfigurationException("The application state \"$state\" is not valid");
            }

        }else{
            throw new ConfigurationException("The configuration project=>application=>state must be 
            defined to make the application run properly");
        }
    }

    /**
     * Return an flag indicating if the state of the application is on DEVELOPMENT mode
     * @return bool
     */
    public function onDevelopment(){
        return $this->applicationState === self::STATE_DEVELOPMENT;
    }

    /**
     * Return an flag indicating if the state of the application is on PRODUCTION mode
     * @return bool
     */
    public function onProduction(){
        return $this->applicationState === self::STATE_PRODUCTION;
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