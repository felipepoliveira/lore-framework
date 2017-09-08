<?php
/**
 * Created by PhpStorm.
 * User: WD-17
 * Date: 08/09/2017
 * Time: 10:09
 */

namespace lore\web;


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

    private function processPage(){
        $htmlContent = file_get_contents($this->view);
        preg_match_all("{{[A-z0-9\"'!@#$%¨&*()_+'`/´[\]^:;::\-<>]*}}",$htmlContent,$matches);
        var_dump($matches[0]);
        die();
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