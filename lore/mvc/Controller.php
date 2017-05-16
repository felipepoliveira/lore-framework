<?php
namespace lore\mvc;

require_once __DIR__ . "/../utils/File.php";

use lore\Lore;
use lore\util\File;
use lore\web\Response;

abstract class Controller
{

    /**
     * @var MvcRouter
     */
    private $mvcRouter;

    /**
     * @var Response
     */
    private $response;

    /**
     * Controller constructor.
     * @param $mvcRouter MvcRouter
     */
    function __construct($mvcRouter)
    {
        $this->mvcRouter = $mvcRouter;
        $this->response = new Response();
    }

    /**
     * @return MvcRouter
     */
    public function getMvcRouter(): MvcRouter
    {
        return $this->mvcRouter;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function render($view, $data = null){
        //Get the full path to the view
        $viewPath = File::checkFileInDirectories($view, $this->mvcRouter->getViewsDirectories());

        //Tell that the response will not redirect
        $this->response->setRedirect(false);

        //If the view is found put data into the response
        if($viewPath){
            //Disable NOTICE reporting
            error_reporting(E_ALL ^ E_NOTICE);

            $this->response->setCode(200);
            $this->response->setData($data);
            $this->response->setUri($viewPath);
        }
        //Otherwise, send code 404
        else{
            $this->response->setCode(404);
        }
    }

    /**
     * Redirect the request to another controller method
     * @param string $uri
     */
    public function redirect($uri){
        //Check if the redirect uri is relative of the project root
        if(strlen($uri) > 0 && $uri[0] !== "/"){
            $this->response->setUri(Lore::app()->getContext()->getRelativePath() . "/$uri" );
        }else{
            //otherwise put the absolute path over the host domain
            $this->response->setUri($uri);
        }
        $this->response->setRedirect(true);
    }

    /**
     * Send data to the client. This method has to be used in api services
     * @param mixed $data - The data that will be send
     * @param int $code - The status code
     */
    public function send($data = null, $code = 200){
        $this->response->setRedirect(false);
        $this->response->setCode($code);
        $this->response->setData($data);
    }

    /**
     * Load the model data with request given data (GET and POST array), validates it if $validate is true
     * and, in case of error, put the errors into response
     * @param Model $model
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool
     */
    public function load(Model $model, $validationMode = null, $validationExceptions = null){
        //Load the model data passing the request
        $model->load(Lore::app()->getRequest());

        //If validate flag is active, do validation
        if(isset($validationRules)){
            $validationResult = $model->validate($validationMode, $validationExceptions ?? []);
            if($validationResult === true){
                return true;
            }else{
                $this->response->setErrors($validationResult);
                return false;
            }
        }else{
            return true;
        }
    }
}