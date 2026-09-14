<?php

class Desafio
{
    public string $nome;
    public string $descricao;
    public string $link_background;
    public int $cd;
    public string $bestAtribute;
    private string $velocidadeTexto;
    private string $danoTexto;
    private string $chance_esquivaTexto;

    public function __construct(string $nome, int $cd, string $descricao, string $link_background, string $bestAtribute, string $velocidadeTexto, string $danoTexto, string $chance_esquivaTexto)
    {
        $this->nome = $nome;
        $this->link_background = $link_background;
        $this->cd = $cd;
        $this->descricao = $descricao;
        $this->bestAtribute = $bestAtribute;
        $this->velocidadeTexto = $velocidadeTexto;
        $this->danoTexto = $danoTexto;
        $this->chance_esquivaTexto = $chance_esquivaTexto;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getLinkBackground(): string
    {
        return $this->link_background;
    }

    public function getCd(): int
    {
        return $this->cd;
    }

    public function getBestAtribute(): string
    {
        return $this->bestAtribute;
    }

    public function getVelocidadeTexto(): string
    {
        return $this->velocidadeTexto;
    }

    public function getDanoTexto(): string
    {
        return $this->danoTexto;
    }

    public function getChanceEsquivaTexto(): string
    {
        return $this->chance_esquivaTexto;
    }

    public function getHtmlAcoes(): string
    {
        $velocidade = "<div onclick=\"usarAcao('AcaoV')\"><span>" . $this->velocidadeTexto . "</span></div>";
        $dano = "<div onclick=\"usarAcao('AcaoD')\"><span>" . $this->danoTexto . "</span></div>";
        $esquiva = "<div onclick=\"usarAcao('AcaoE')\"><span>" . $this->chance_esquivaTexto . "</span></div>";

        $embaralhador = [$velocidade, $dano, $esquiva];

        shuffle($embaralhador);

        return implode(' ', $embaralhador);
    }
}