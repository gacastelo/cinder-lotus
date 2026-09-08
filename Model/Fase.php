<?php

class Fase
{
    public int $id;
    public string $nome;
    public int $x;
    public int $y;
    public array $conexoes;
    public bool $bloqueada;
    public int $dificuldade;
    public string $tipo;

    public function __construct(int $id, string $nome, int $x, int $y, array $conexoes, int $dificuldade, string $tipo, bool $bloqueada = true)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->x = $x;
        $this->y = $y;
        $this->conexoes = $conexoes;
        $this->bloqueada = $bloqueada;
        $this->dificuldade = $dificuldade;
        $this->tipo = $tipo;
    }

    public function unlock(): void
    {
        $this->bloqueada = false;
    }

    public function getConexoes(): array
    {
        return $this->conexoes;
    }
    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function isBloqueada(): bool
    {
        return $this->bloqueada;
    }
    public function getDificuldade(): int
    {
        return $this->dificuldade;
    }
}