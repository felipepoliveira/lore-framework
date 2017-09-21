<?php

use lore\mvc\Model;

require_once "Produto.php";

/**
 * Class Usuario
 * @entity tabela_usuario
 * @repository lore/mysql
 */
class Usuario extends Model
{
    use \lore\persistence\Entity;

    /**
     * @id
     * @auto
     * @field
     * @var
     */
    private $id;

    /**
     * @field
     * @var string
     * @filterVar email
     */
    private $email;

    /**
     * @field
     * @var string
     * @min 6
     * @max 30
     */
    private $senha;

    /**
     * @var string
     * @field
     * @transactional
     */
    private $token;

    /**
     * @var Produto[]
     */
    private $produtos = [];

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

    /**
     * @return Produto[]
     */
    public function getProdutos(): array
    {
        return $this->produtos;
    }

    /**
     * @param Produto[] $produtos
     */
    public function setProdutos(array $produtos)
    {
        $this->produtos = $produtos;
    }

    public  function getIdentifier()
    {
        return $this->getId();
    }


}