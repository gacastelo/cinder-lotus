<?php

class Desafio
{
    public string $nome;
    public string $descricao;
    public string $link_background;
    public int $cd;
    public string $bestAtribute;

    public function __construct(string $nome, int $cd, string $descricao, string $link_background, string $bestAtribute)
    {
        $this->nome = $nome;
        $this->link_background = $link_background;
        $this->cd = $cd;
        $this->descricao = $descricao;
        $this->bestAtribute = $bestAtribute;
    }

    public function getLinkBackground(): string
    {
        return $this->link_background;
    }
}