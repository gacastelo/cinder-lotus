<?php
class Estatistica
{
    private string $nome;
    private int $ataquesTotais = 0;
    private int $ataquesAcertados = 0;
    private int $itensConsumidos = 0;
    private int $combatesGanhos = 0;
    private int $combatesFugas = 0;
    private int $desafiosSuperados = 0;
    private int $desafiosFugas = 0;
    private DateInterval $tempoConclusao;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function increaseAtaquesTotais(): void
    {
        $this->ataquesTotais++;
    }

    public function increaseAtaquesAcertados(): void
    {
        $this->ataquesAcertados++;
    }

    public function increaseItensConsumidos(): void
    {
        $this->itensConsumidos++;
    }

    public function increaseCombatesGanhos(): void
    {
        $this->combatesGanhos++;
    }

    public function increaseCombatesFugas(): void
    {
        $this->combatesFugas++;
    }

    public function increaseDesafiosSuperados(): void
    {
        $this->desafiosSuperados++;
    }

    public function increaseDesafiosFugas(): void
    {
        $this->desafiosFugas++;
    }

    public function setTempoConclusao($tempo): void
    {
        $this->tempoConclusao = $tempo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getAtaquesTotais(): int
    {
        return $this->ataquesTotais;
    }

    public function getAtaquesAcertados(): int
    {
        return $this->ataquesAcertados;
    }

    public function getItensConsumidos(): int
    {
        return $this->itensConsumidos;
    }

    public function getCombatesGanhos(): int
    {
        return $this->combatesGanhos;
    }

    public function getCombatesFugas(): int
    {
        return $this->combatesFugas;
    }

    public function getDesafiosSuperados(): int
    {
        return $this->desafiosSuperados;
    }

    public function getDesafiosFugas(): int
    {
        return $this->desafiosFugas;
    }

    public function getTempoConclusao(): DateInterval
    {
        return $this->tempoConclusao;
    }


}
