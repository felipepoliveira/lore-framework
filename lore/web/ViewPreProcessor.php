<?php
/**
 * Created by PhpStorm.
 * User: WD-17
 * Date: 08/09/2017
 * Time: 10:09
 */

namespace lore\web;


use lore\mvc\Controller;
use lore\mvc\ViewController;

class ViewPreProcessor
{

    private $page;
    private $view;
    private $data;

    /**
     * ViewPreProcessor constructor.
     * Request the view and the data to proccess the page setting it content.
     * @param $view
     * @param $data
     */
    function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Match the mustaches in the page with the content sent by the Controller in 'data' to be rendered.
     * @see Controller
     */
    private function processPage(){
        $htmlContent = file_get_contents($this->view);
        preg_match_all("{{{[A-z0-9\"'!@#$%¨&*()_+'`/´[\]^:;\-<>]*}}}",$htmlContent,$matches);
        extract($this->data);
        foreach ($matches[0] as $expression){
            $start = strrpos($expression,"{");
            $end = strrpos($expression, "}")-2;
            $expression = substr($expression,2,($end-$start));
            eval('$value = $'.$expression.';');
            $htmlContent = str_replace("{{".$expression."}}",$value,$htmlContent);
        }
        $this->page = $htmlContent;
    }

    /**
     * @return string
     * Return the processed page as HTML text to be rendered.
     */
    public function getPageProcessed(){
        $this->processPage();
        return $this->page;
    }

}