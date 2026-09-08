<?php
require "../AbsModel/AbsItem.php";
class Consumivel extends AbsItem
{
    private array $efeito;

    public function __construct($nome, $descricao, $efeito)
    {
        $this->id = uniqid("con");
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->efeito = $efeito;
        $this->tipo = "Consumivel";
    }

    public function getEfeito(): array
    {
        return $this->efeito;
    }
}