<?php

require_once __DIR__ . "/../../dao/ItemDAO.php";
require_once __DIR__ . "/../../models/Item.php";

use lore\mvc\Controller;

class ItemApiController extends Controller
{
    /**
     * @var ItemDAO
     */
    private $dao;
    function __construct(MvcRouter $mvcRouter)
    {
        parent::__construct($mvcRouter);
        $this->dao = new ItemDAO();
    }

    /**
     * @return Item
     */
    public function getModel(): \lore\mvc\Model
    {
        return parent::getModel();
    }

    private function loadModel() : Item{
        $this->loadAndValidateModel();
        $model = $this->getModel();
        $model->setName(trim($model->getName()));
    }

    /**
     * @uri /
     * @method post
     */
    public function cadastrar(){
        $model = $this->loadModel();
        if($model->validate(\lore\mvc\ValidationModes::ALL)){

        }else{
            $data = ["message" => ""];
        }
    }

    public function createNewModelInstance()
    {

    }

}