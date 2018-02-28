<?php

use lore\mvc\Model;

class Usuario extends Model
{
    /**
     * @id
     * @var
     */
    private $id;

    private $email;

    private $senha;

    private $token;
    /**
     * @one
     * @var \lore\persistence\Entity
     */
    private $endereco;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    public function gerarToken(){
        $this->token = hash('sha256',$this->getEmail() . $this->getSenha());
    }


}