<?php
namespace lore\web;


require_once "HeaderEntity.php";


class ResponseCache extends HeaderEntity
{
    /**
     * Tell to navigator that cache is not allowed
     */
    public function noCache(){
        $this->headerValues[] = "no-cache";
    }

    /**
     * Tell to navigator to revalidate the cache
     */
    public function revalidate(){
        $this->headerValues[] = "must-revalidate";
    }

    public  function getHeaderFieldName(): string
    {
        return "Cache-Control";
    }
}