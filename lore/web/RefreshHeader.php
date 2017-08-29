<?php
namespace lore\web;

require_once "HeaderEntity.php";


class RefreshHeader extends HeaderEntity
{
    public function getHeaderFieldName(): string
    {
        return "Refresh";
    }

    public function refreshTo($url, $seconds = 0){
        $this->headerValues[] = $seconds;
        $this->headerValues[] = "url=$url";
    }


}