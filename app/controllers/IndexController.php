<?php
use lore\mvc\Controller;

class IndexController extends Controller
{
    /**
     * @method get
     * @uri /
     */
    public function index($id){
        $this->render("index.php");
    }

    /**
     * @param int $id
     * @method get
     * @uri /oi/$id/$nome
     */
    public function oi($id, $nome){
        $this->send([$id, $nome]);
    }
}