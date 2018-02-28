<?php
namespace lore\web;

require_once __DIR__ . "/../DataFormatter.php";


class DefaultDataFormatter extends DataFormatter
{
    public function formatToJson($data)
    {
        return json_encode($data);
    }

    public function formatToXml($data)
    {
        if(is_array($data)){

        }else{
            throw new \InvalidArgumentException("The \$data parameter must be  an array map");
        }
    }

    public function jsonToArray(string $json)
    {
        return json_decode($json, true);
    }

    public function xmlToArray($xml)
    {
        return false;
    }


    public function formatAsContentType(): string
    {
        switch ($this->getFormatType()){
            case DataFormatter::JSON:
                return "application/json";
            case DataFormatter::TEXT:
                return "text/text";
            case DataFormatter::XML:
                return "application/xml";
                default: return null;
        }
    }


}