<?php
namespace lore\web;


require_once "HeaderEntity.php";

/**
 * Class CacheHeader - Add cache data into the response header.
 * An object of this class can be found in the an response object and must be used inside it like:
 * $response->getCacheHeader()-><method>.
 * -------------------
 * Example: If you want to send 'no-cache' to tell to the navigator that caches can not be made you should use
 * the CacheHeader::noCache() method. Inside the $response object it should be:
 * $response->getCacheHeader()->noCache()
 *
 * @package lore\web
 */
class CacheHeader extends HeaderEntity
{
    /**
     * Tell to navigator that cache could not be stored in the response,
     * applying the the header: Cache-Control: no-cache;
     */
    public function noCache(){
        $this->headerValues[] = "no-cache";
    }

    /**
     * Tell to navigator that cache could not be stored in the response,
     * applying the the header: Cache-Control: must-revalidate;
     */
    public function revalidate(){
        $this->headerValues[] = "must-revalidate";
    }

    public  function getHeaderFieldName(): string
    {
        return "Cache-Control";
    }
}