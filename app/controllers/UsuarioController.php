<?php

use lore\mvc\Controller;
use lore\Lore;

class UsuarioController extends Controller
{
    /**
     * @uri /buscar/$id
     */
    public function mostrar(){
        $this->render("index.php");
    }

    /**
     * @uri /trocarAvatar
     */
    public function trocarAvatar()
    {
        $imagem = $_FILES["imagem"] ?? null;
        if(isset($imagem)){
            $usuario = daoUsuario->buscar($_SESSION["id"]);
            $nomeDoArquivo = dirname(dirname(__DIR__)) . $usuario->getNomeUsuario()
        }
    }
}