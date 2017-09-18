<?php
namespace lore\web;

use lore\Configurations;

/**
 * Class ResourcesManager - Abstract class that defines how the server can or can not send files to
 * the client
 * @package lore\web
 */
abstract class ResourcesManager
{
    public const    MODE_ALLOW = 1 << 0, MODE_DENY = 1 << 1;

    /**
     * The client request
     * @var Request
     */
    protected $request;

    /**
     * Store the exception regex matches files
     * @var array
     */
    protected $exceptions = [];

    /**
     * Flag that indicates if the php script can be processed
     * @var bool
     */
    protected $allowScriptProcessing = false;

    /**
     * Store the php script extensions
     * @var array
     */
    protected $scriptExtensions = [];

    /**
     * @var int
     */
    protected $mode;

    /**
     * ResourcesManager constructor - Construct the ResourcesManager object passing the request sent by the client
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->mode = $this->loadMode();
        $this->exceptions = Configurations::get("project", "resourcesManager")["exceptions"];
        $this->allowScriptProcessing = Configurations::get("project", "resourcesManager")["allowScriptProcessing"];
        $this->scriptExtensions = Configurations::get("project", "resourcesManager")["scriptExtensions"];
    }

    /**
     * The exception files array. This array is extracted from project.php => resourcesManager => exception config
     * @return array
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * The permission mode. This int is extracted from project.php => resourcesManager => mode config
     * Allow: 1
     * Deny: 2
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * Get the client request
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Flag indicating if the ResourcesManager will send a response asking to the ResponseManager to process the
     * php script
     * @return bool
     */
    public function isScriptingProcessingAllowed(): bool
    {
        return $this->allowScriptProcessing;
    }

    /**
     * Array with all php script extensions. This array is extracted from project.php => resourcesManager =>
     * scriptExtensions config
     * @return array
     */
    public function getScriptExtensions(): array
    {
        return $this->scriptExtensions;
    }

    /**
     * Get the content type based in the requested resource will be sent to the client
     * @return string
     */
    public abstract function getContentType() : string;

    /**
     * Get the absolute path to the server resource
     * @return string
     */
    public abstract function getResource() : string ;

    /**
     * Check if the resource can be sent to the client
     * @param Request $request
     * @return bool
     */
    public abstract function isAllowed(Request $request) : bool;

    /**
     * Check if the requested uri is a server resource
     * @param Request $request
     * @return bool
     */
    public abstract function isAResource(Request $request) : bool;

    /**
     * Return an flag indicating if the requested uri make an reference to a php script
     * @param Request $request
     * @return bool
     */
    public abstract function isAScript(Request $request) : bool ;

    /**
     * Check if the resource exists in the server
     * @param Request $request
     * @return bool
     */
    public abstract function resourceExists(Request $request) : bool ;

    /**
     * Handle the resource creating an Response object that will be sent to the ResponseManager
     * @see  Response
     * @see  ResponseManager
     * @return Response
     */
    public function handle() : Response
    {
        //Set the response object
        $response = new Response();;

        //If the resource exists...
        if($this->resourceExists($this->request)) {

            //If is not an php script...
            if (!$this->isAScript($this->request)) {
                $response->setCharset(null);

                /*
                 * Check if the resource is allowed to be sent to the client. If it is give 200 OK, set the response
                 * content type based on the requested resource and read the resource file.
                 */
                if ($this->isAllowed($this->request)) {
                    $response->setCode(200);
                    $response->setContentType($this->getContentType());
                    $response->setUri($this->getResource());
                    $response->setSendResource(true);
                } else {
                    //Otherwise: Give an 403 forbidden
                    $response->setCode(403);
                }
            }
            //If is a script check if it can be executed
            else if($this->isScriptingProcessingAllowed()){
                $response->setCode(200);
                $response->setUri($this->getResource());
            }else{
                //Otherwise: define forbidden as the default code
                $response->setCode(403);
            }
        }else{
            $response->setCode(404);
        }
        return $response;
    }

    /**
     * Load the resource permission mode from the configuration file project[resourcesManagers=>mode]
     * @return int
     */
    protected function loadMode()
    {
        $mode = Configurations::get("project", "resourcesManager")["mode"];

        switch (strtolower($mode)){
            case "allow":
                return DefaultResourcesManager::MODE_ALLOW;
            default:
                return DefaultResourcesManager::MODE_DENY;
        }
    }

    /**
     * Check if an file matches with the given exceptions patterns in config file: project.resourcesManager.matches array
     * @param $file
     * @return bool
     */
    public function matches($file){
        foreach ($this->getExceptions() as $match){
            $match = "/" . $match . "/";
            if(preg_match($match, $file)){
                return true;
            }
        }
        return false;
    }

}