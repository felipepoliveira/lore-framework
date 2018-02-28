<?php
/**
 * Created by PhpStorm.
 * User: WD-17
 * Date: 08/09/2017
 * Time: 10:09
 */

namespace lore\web;


use lore\Configurations;
use lore\Lore;
use lore\mvc\Controller;

abstract class ViewPreProcessor
{

    private $data;
    private $page;
    private $buildDirectory;

    /**
     * ViewPreProcessor constructor.
     * Request the view and the data to proccess the page setting it content.
     */
    function __construct()
    {
        $this->buildDirectory = Configurations::get("app","viewPreProcessor")["buildDirectory"];
    }

    /**
     * Match the mustaches in the page with the content sent by the Controller in 'data' to be rendered.
     * @see Controller
     * @param $page
     * @param $data
     */
    public function processView($page, $data){
        $this->data = $data;
        $content = file_get_contents($page);
        $this->page = $content;
    }

    /**
     * @return string
     * Return the processed page as HTML text to be rendered.
     */
    public function getViewProcessed(){
        $dir = Lore::app()->getContext()->getAbsolutePath() . "\\private\\$this->buildDirectory";
        if(!is_dir($dir)){
            mkdir($dir);
        }
        $fileName = explode('.',Lore::app()->getResponse()->getUri())[0];
        $fileName = explode('/',$fileName);
        $fileName = $fileName[count($fileName)-1];
        $filePath = $dir."\\$fileName.php";
        if(Lore::app()->getContext()->onDevelopment() || !file_exists($filePath)){
            $file = fopen("$filePath",'w');
            fwrite($file,$this->page);
            fclose($file);
        }
        return $filePath;
    }

}