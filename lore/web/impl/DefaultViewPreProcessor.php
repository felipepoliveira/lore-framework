<?php
/**
 * Created by PhpStorm.
 * User: Guilherme Duarte
 * Date: 27/02/2018
 * Time: 15:48
 */

namespace lore\web;

require_once __DIR__ . "/../ViewPreProcessor.php";

use lore\Configurations;
use lore\Lore;
use lore\mvc\Controller;
use lore\mvc\View;
use lore\persistence\EntityMetadata;


class DefaultViewPreProcessor extends ViewPreProcessor
{

    private $data;
    private $page;
    private $minify;
    private $buildDirectory;

    // THE REGEX TO GET THE TEXT
    public const VARIABLE_REGEX = "[A-z0-9\"'!@#$%¨&*(.)=_+'`/´[\]^:;\- ]";

    /**
     * ViewPreProcessor constructor.
     * Request the view and the data to proccess the page setting it content.
     */
    function __construct()
    {
        $this->minify = Configurations::get("app", "viewPreProcessor")["minify"];
        $this->buildDirectory = Configurations::get("app", "viewPreProcessor")["buildDirectory"];
    }

    /**
     * Match the mustaches in the page with the content sent by the Controller in 'data' to be rendered.
     * @see Controller
     */
    public function processView($page, $data)
    {
        $this->data = $data;
        $content = file_get_contents($page);
        $content = $this->processVariables($content);
        $content = $this->processTags($content);
        $this->page = $content;
    }

    /**
     * This will parse all the variables into PHP variables.
     * @param $content String The .lore.php content with the variables to be parsed.
     * @return String The content already parsed and ready to be rendered.
     */
    private function processVariables($content)
    {
        preg_match_all("{{{" . DefaultViewPreProcessor::VARIABLE_REGEX . "*}}}", $content, $matches);
        extract($this->data);



        foreach ($matches[0] as $expression) {
            $start = strrpos($expression, "{");
            $end = strrpos($expression, "}") - 2;
            $expression = substr($expression, 2, ($end - $start));
            $expressionExploded = explode('.',$expression);
            $expressionResult = $expressionExploded[0];
            for($i = 1 ; $i < count($expressionExploded) ; $i++){
                $expressionResult.='->get'.ucfirst($expressionExploded[$i]).'()';
            }
            $content = str_replace("{{" . $expression . "}}", '<?=$' . $expressionResult. ';?>', $content);
        }

        return $content;
    }

    /**
     * This will turn all the functions and procedures into functions in PHP.
     * @param $content String The .lore.php content with the functions to be parsed.
     * @return String The content already parsed and ready to be rendered.
     */
    private function processTags($content)
    {
        preg_match_all("{@" . DefaultViewPreProcessor::VARIABLE_REGEX . "*(".DefaultViewPreProcessor::VARIABLE_REGEX."*)\\{?}", $content, $matches);
        foreach ($matches[0] as $func) {

            $functionStart = strpos($func, "@");
            $functionEnd = strpos($func, "(");
            $function = substr($func, $functionStart + 1, $functionEnd - 1);

            $expressionStart = strpos($func, "(");
            $expressionEnd = strpos($func,")");
            $expression = substr($func, $expressionStart + 1, ($expressionEnd - $expressionStart) -1);

            $function = $this->getTheFunction($function, $expression);
            $content = str_replace($func, '<?php '.$function.'?>', $content);
        }
        $content = str_replace('}','<?php } ?>',$content);
        return $content;
    }

    /**
     * @return string
     * Return the processed page as HTML text to be rendered.
     */
    public function getViewProcessed()
    {
        $dir = Lore::app()->getContext()->getAbsolutePath() . "\\private\\$this->buildDirectory";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $fileName = explode('.', Lore::app()->getResponse()->getUri())[0];
        $fileName = explode('/', $fileName);
        $fileName = $fileName[count($fileName) - 1];
        $filePath = $dir . "\\$fileName.php";
        if (Lore::app()->getContext()->onDevelopment() || !file_exists($filePath)) {
            $file = fopen("$filePath", 'w');
            fwrite($file, $this->page);
            fclose($file);
        }
        return $filePath;
    }

    public function getTheFunction($function, $expression)
    {
        switch ($function) {
            case "error":
                break;
            case "foreach":
                $expression = explode(' as ',$expression);
                return "foreach($$expression[0] as $$expression[1]){";
                break;
            case "for":
                break;
            case "ul":
                break;
            case "valid":
                break;
            case "invalid":
                break;
        }
    }

}