<?php
namespace lore\web;
use lore\ConfigurationException;
use lore\Configurations;

/**
 * Format data to will be sent into response
 * @package lore\web
 */
abstract class DataFormatter
{
    public const    TEXT = 1,
                    JSON = 2,
                    XML = 3;

    private $formatType;

    private $defaultFormatType;

    //DEFAULT CONSTRUCTOR
    function __construct()
    {
        $this->loadDefaultFormatType();
        //Assume the format type as the default format type from file
        $this->formatType = $this->defaultFormatType;
    }

    private function loadDefaultFormatType(){
        //Get the default format type from configuration file
        $defaultFormatType =
            Configurations::get("project", "responseManager")["dataFormatter"]["defaultFormatType"];
        $defaultFormatType = strtolower($defaultFormatType);

        switch ($defaultFormatType){
            case "json":
                $this->defaultFormatType = DataFormatter::JSON;
                break;
            case "txt":
                $this->defaultFormatType = DataFormatter::TEXT;
                break;
            case "xml":
                $this->defaultFormatType = DataFormatter::XML;
                break;
            default:
                throw new ConfigurationException("The configuration $defaultFormatType from project.php is not valid");
        }
    }

    /**
     * Format an data to a specific format type (JSON, XML, Text, etc.).
     * @param $data
     * @param int $format - The format type DataFormatter.JSON, DataFormatter.XML etc...
     * @throws \InvalidArgumentException - In case of the $format parameter is invalid
     * @return string The formatted value
     */
    public function format($data, $format = null){
        if(!isset($format) || is_integer($format)){
            $format = $this->defaultFormatType;
        }else{
            $this->formatType = $format;
        }

        switch ($format){
            case DataFormatter::JSON:
                return $this->formatToJson($data);
            case DataFormatter::TEXT:
                return $this->formatToText($data);
            case DataFormatter::XML:
                return $this->formatToXml($data);
            default:
                throw new \InvalidArgumentException("The parameter \$format must be one of the constants of DateFormatter");
        }

        return null;
    }

    /**
     * Get the default format type of the formatter
     * @return mixed
     */
    public function getDefaultFormatType()
    {
        return $this->defaultFormatType;
    }

    /**
     * @return mixed
     */
    public function getFormatType()
    {
        return $this->formatType;
    }

    /**
     * @param mixed $formatType
     */
    public function setFormatType($formatType)
    {
        $this->formatType = $formatType;
    }

    /**
     * Format an given data to json format
     * @param $data - The data that will be formatted
     * @return string|null
     */
    public abstract function formatToJson($data);


    /**
     * Format an valid JSON string into array
     * @param string $json
     * @return mixed
     */
    public abstract function jsonToArray(string $json);

    /**
     * Format an given data into xml
     * @param $data - The data that will be formatted
     * @return string|null
     */
    public abstract function formatToXml($data);

    /**
     * Format an valid XML string to PHP array
     * @param $xml
     * @return mixed
     */
    public abstract function xmlToArray($xml);

    /**
     * Return the value of the format as content type description
     * @return string
     */
    public abstract function formatAsContentType() : string;
}