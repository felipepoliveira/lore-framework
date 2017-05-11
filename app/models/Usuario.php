<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 10/05/2017
 * Time: 18:05
 */
class Usuario extends Model
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nome;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }

    public  function validate()
    {
        $errors = [];

        if(!is_int($this->id)){
            $errors["id"] = "O id deve ser informado";
        }

        if(!is_string($this->nome) || strlen($this->nome) < 3){
            $errors["nome"] = "O nome é obrigatório e deve conter no mínimo 3 caracteres";
        }

        if(count($errors) > 0){
            return $errors;
        }else{
            return true;
        }
    }
}