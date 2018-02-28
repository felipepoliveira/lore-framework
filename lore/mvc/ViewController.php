<?php
namespace lore\mvc;

require_once __DIR__ . "/../utils/File.php";
require_once "Controller.php";
require_once "ViewNotFoundException.php";

use lore\util\File;

abstract class ViewController extends Controller
{
    /**
     * @param string $view
     * @param array $data
     */
    public function render(string $view, array $data = []){
        //Get the full path to the view
        $viewPath = File::checkFileInDirectories($view, $this->mvcRouter->getViewsDirectories());

        //Tell that the response will not redirect
        $this->response->setRedirect(false);

        //If the view is found put data into the response
        if($viewPath){
            //Disable NOTICE reporting
            error_reporting(E_ALL ^ E_NOTICE);

            $this->response->setCode(200);
            $this->response->setUri($viewPath);

            //Put data into response
            foreach ($data as $key => $value){
                $this->response->put($key, $value);
            }
        }
        else{
            throw new ViewNotFoundException("The view \"$view\" does not exists in the view directories");
        }
    }
}