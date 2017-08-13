<?php
namespace lore\web;

require_once __DIR__ . "/../DataFormatter.php";


class DefaultDataFormatter extends DataFormatter
{
    public function formatJson($data)
    {
        return json_encode($data);
    }

    public function formatToText($data)
    {
        return (is_array($data) || is_object($data))?  var_dump($data) : (string) $data;
    }

    public function formatToXml($data)
    {
        if(is_array($data)){

        }else{
            throw new \InvalidArgumentException("The \$data parameter must be  an array map");
        }
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