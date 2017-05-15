<?php
namespace lore\web;

class DefaultResourcesManager extends ResourcesManager
{
    /**
     * The resource extension
     * @var string
     */
    private $extension;

    /**
     * The resource filename
     * @var string
     */
    private $filePath;


    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->extension = pathinfo($request->getRequestedUri(), PATHINFO_EXTENSION);
        $this->filePath = $_SERVER["DOCUMENT_ROOT"] . $request->getRawRequestedUri();
    }

    /**
     * Return the mime type of the file as the content type
     * @return string
     */
    public function getContentType(): string
    {
        return mime_content_type($this->filePath);
    }

    public function getResource(): string
    {
        return $this->filePath;
    }

    public function isAllowed(Request $request): bool
    {
        switch ($this->mode){
            case DefaultResourcesManager::MODE_ALLOW:
                return !$this->matches($this->filePath);
            default:
                return $this->matches($this->filePath);
        }
    }

    public function isAResource(Request $request): bool
    {
        return $this->extension !== "";
    }

    public  function isAScript(Request $request): bool
    {
        foreach ($this->getScriptExtensions() as $scriptExtension) {
            if($this->extension === $scriptExtension){
                return true;
            }
        }

        return false;
    }

    public function resourceExists(Request $request): bool
    {
        return file_exists($this->filePath);
    }
}