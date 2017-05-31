<?php
use lore\web\Session;

class ItemDAO
{
    protected function getItemsStorage(){
        if(!Session::contains("items")){
            Session::put("items", []);
        }

        return Session::get("items");
    }

    /**
     * @param Item $item
     */
    public function insert($item){
        $this->getItemsStorage()[$item->getId()] = $item->getName();
    }

    public function delete($id){
        $itemStorage = $this->getItemsStorage();
        if(isset($itemStorage[$id])){
            unset($itemStorage[$id]);
        }
    }
}