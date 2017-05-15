<?php
namespace lore\web;

use lore\Configurations;


class DefaultResourcesManager extends AbstractResourcesManager
{

    /**
     * Store the allowed files
     * @var array
     */
    private $allowedFiles = [];

    /**
     * Store the dennied files
     * @var array
     */
    private $deniedFiles = [];

    private const REGEX_IS_RESOURCE = "*\\.*";

    function __construct()
    {
        $this->allowedFiles = Configurations::get("project", "resourcesManager")["allow"];
        $this->deniedFiles = Configurations::get("project", "resourcesManager")["deny"];
    }

    public function resourceExists(Request $request)
    {
        // TODO: Implement resourceExists() method.
    }

    public function isAResource(Request $request)
    {
        return preg_match(DefaultResourcesManager::REGEX_IS_RESOURCE, $request->getRequestedUri(), $matches);
    }

    public function isAllowed(Request $request)
    {
        // TODO: Implement isAllowed() method.
    }

    public function handle(Request $request)
    {
        if(!$this->isAllowed($request)){
            http_response_code(403);
        }else{

        }
    }

    /**
     * @return array
     */
    public function getAllowedFiles(): array
    {
        return $this->allowedFiles;
    }

    /**
     * @return array
     */
    public function getDeniedFiles(): array
    {
        return $this->deniedFiles;
    }

}