<?php
namespace lore\mvc;

use lore\Lore;
use lore\web\Response;

abstract class Controller
{

    /**
     * @var MvcRouter
     */
    protected $mvcRouter;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Controller constructor.
     * @param $mvcRouter MvcRouter
     */
    function __construct($mvcRouter)
    {
        $this->mvcRouter = $mvcRouter;
        $this->response = new Response();
        $this->model = $this->createNewModelInstance();
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
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Return the controller model
     * @return Model|null
     */
    public abstract function createNewModelInstance();

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

    public function putModelInResponse(){
        //Convert the model into an one-dimensional (plain) array an put the values into response
        foreach ($this->getModel()->toArray() as $key => $value){
            $this->getResponse()->put("model." .  $key, $value);
        }
    }

    /**
     * Load the model data with request given data (GET and POST array), validates it if $validate is true and,
     * in case of error, put the errors into response
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool
     */
    public function loadAndValidateModel($validationMode = ValidationModes::ALL, $validationExceptions = null){
        $this->loadModel();
        return $this->validateModel($validationMode, $validationExceptions);
    }

    /**
     * Load the model data with request given data (GET and POST array)
     * @return void
     */
    public function loadModel(){
        //Load the model data passing the request
        $this->model->load(Lore::app()->getRequest());
    }

    /**
     * validate the controller model
     * and, in case of error, put the errors into response
     * @param int $validationMode
     * @param array $validationExceptions
     * @return bool
     */
    public function validateModel($validationMode = ValidationModes::ALL, $validationExceptions = null){
        //Only validate if validation mode is inputed
        if(isset($validationMode)){
            //Validate the model and, if errors were found, send it to the response
            $validationResult = $this->model->validate($validationMode, $validationExceptions ?? [], "model.");
            if($validationResult !== true){
                $this->response->setErrors($validationResult);
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}