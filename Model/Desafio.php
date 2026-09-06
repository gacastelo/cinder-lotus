<?php

class Desafio
{
    public string $nome;
    public string $descricao;
    public string $link_backgroung;
    public int $cd;
    public string $bestAtribute;

    public function __construct(string $nome, int $cd, string $descricao, string $link_backgroung, string $bestAtribute)
    {
        $this->nome = $nome;
        $this->link_backgroung = $link_backgroung;
        $this->cd = $cd;
        $this->descricao = $descricao;
        $this->bestAtribute = $bestAtribute;
    }
}