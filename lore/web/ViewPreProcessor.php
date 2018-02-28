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

class ViewPreProcessor
{

    private $data;
    private $page;
    private $minify;
    private $buildDirectory;

    public const VARIABLE_REGEX = "[A-z0-9\"'!@#$%¨&*(.)=_+'`/´[\]^:;\-<>]";

    /**
     * ViewPreProcessor constructor.
     * Request the view and the data to proccess the page setting it content.
     */
    function __construct()
    {
        $this->minify = Configurations::get("app", "viewPreProcessor")["minify"];
        $this->buildDirectory = Configurations::get("app","viewPreProcessor")["buildDirectory"];
    }

    /**
     * Match the mustaches in the page with the content sent by the Controller in 'data' to be rendered.
     * @see Controller
     */
    public function processView($page, $data){
        $this->data = $data;
        $content = file_get_contents($page);
        $content = $this->processVariables($content);
        $content = $this->processTags($content);
        $this->page = $content;
    }

    /**
     * @param $content The HTML content with the variables to be parsed.
     * @return String The content already parsed and ready to be rendered.
     */
    private function processVariables($content) {
        preg_match_all("{{{".ViewPreProcessor::VARIABLE_REGEX."*}}}",$content,$matches);
        extract($this->data);
        foreach ($matches[0] as $expression){
            $start = strrpos($expression,"{");
            $end = strrpos($expression, "}")-2;
            $expression = substr($expression,2,($end-$start));
            $content = str_replace("{{".$expression."}}",'<?=$'.$expression.';?>',$content);
        }
        return $content;
    }

    private function processTags($content){
        preg_match_all("{@".ViewPreProcessor::VARIABLE_REGEX."*}",$content,$matches);
        foreach ($matches[0] as $expression){
            var_dump($expression);
        }
        die();
        return $content;
    }

    /**
     * @return string
     * Return the processed page as HTML text to be rendered.
     */
    public function getViewProcessed(){
        $dir = Lore::app()->getContext()->getAbsolutePath()."\\$this->buildDirectory";
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