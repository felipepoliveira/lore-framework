<?php
namespace lore\web;


abstract class HeaderEntity
{
    /**
     * @var array
     */
    protected $headerValues;

    /**
     * Return the values to be applied in header value
     * @return array
     */
    public function getHeaderValues()
    {
        return $this->headerValues;
    }

    /**
     * The header definition name
     * @return string
     */
    public abstract function getHeaderFieldName() : string;

    /**
     * Put the header values into the response
     */
    public function putHeader(){
        $header = $this->parseToHeaderValue();
        if($header){
            header($header);
        }
    }

    /**
     * Parse all the values in headerValues into a string to be applied in header. Return false if the header values is
     * empty
     * @return bool|string
     */
    public function parseToHeaderValue(){
        $count = count($this->getHeaderValues());
        if($count > 0){
            $header = $this->getHeaderFieldName() . ": ";
            for($i = 0; $i < $count; $i++){
                $header .= $this->headerValues[$i];
                if($i < $count-1){
                    $header .= ", ";
                }
            }
            return $header;
        }
        return false;
    }
}