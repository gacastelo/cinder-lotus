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
    private int $desafiosFalhos = 0;
    private int $danoCausado = 0;
    private int $danoSofrido = 0;
    private int $tempoConclusao;
    private bool $saved = false;

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

    public function increaseDesafiosFalhos(): void
    {
        $this->desafiosFalhos++;
    }

    public function increaseDanoCausado(int $value): void
    {
        $this->danoCausado += $value;
    }

    public function increaseDanoSofrido(int $value): void
    {
        $this->danoSofrido += $value;
    }
    public function setTempoConclusao(int $tempo): void
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

    public function getDesafiosFalhos(): int
    {
        return $this->desafiosFalhos;
    }

    public function getTempoConclusao(): int
    {
        return $this->tempoConclusao;
    }

    public function getDanoCausado(): int
    {
        return $this->danoCausado;
    }

    public function getDanoSofrido(): int
    {
        return $this->danoSofrido;
    }

    public function isSaved(): bool
    {
        return $this->saved;
    }

    public function save(): void
    {
        $this->saved = true;
    }

    public function getHTMLTable(): void
    {
        echo "
    <table id='estatisticaTable'>
        <tbody>
            <tr>
                <th>Nome</th>
                <td>" . $this->getNome() . "</td>
            </tr>
            <tr>
                <th>Ataques Totais</th>
                <td>" . $this->getAtaquesTotais() . "</td>
            </tr>
            <tr>
                <th>Ataques Acertados</th>
                <td>" . $this->getAtaquesAcertados() . "</td>
            </tr>
            <tr>
                <th>Itens Consumidos</th>
                <td>" . $this->getItensConsumidos() . "</td>
            </tr>
            <tr>
                <th>Combates Ganhos</th>
                <td>" . $this->getCombatesGanhos() . "</td>
            </tr>
            <tr>
                <th>Combates Fugas</th>
                <td>" . $this->getCombatesFugas() . "</td>
            </tr>
            <tr>
                <th>Desafios Superados</th>
                <td>" . $this->getDesafiosSuperados() . "</td>
            </tr>
            <tr>
                <th>Desafios Fugas</th>
                <td>" . $this->getDesafiosFugas() . "</td>
            </tr>
            <tr>
                <th>Desafios Falhos</th>
                <td>" . $this->getDesafiosFalhos() . "</td>
            </tr>
            <tr>
                <th>Dano Causado</th>
                <td>" . $this->getDanoCausado() . "</td>
            </tr>
            <tr>
                <th>Dano Sofrido</th>
                <td>" . $this->getDanoSofrido() . "</td>
            </tr>
            <tr>
                <th>Tempo Conclusão</th>
                <td>" . $this->getTempoConclusao() . "</td>
            </tr>
        </tbody>
    </table>";
    }

}
