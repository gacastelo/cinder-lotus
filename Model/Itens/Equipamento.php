<?php
class Equipamento extends AbsItem
{
    private int $velocidade_modifier;
    private int $dano_modifier;
    private int $chance_esquiva_modifier;
    private int $vida_maxima_modifier;
    private bool $is_equipped;

    public function __construct($nome, $tipo, $descricao, $vida_maxima_modifier, $velocidade_modifier, $dano_modifier, $chance_esquiva_modifier,$is_equipped)
    {
        $this->id = uniqid("eqp");
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->descricao = $descricao;
        $this->vida_maxima_modifier = $vida_maxima_modifier;
        $this->velocidade_modifier = $velocidade_modifier;
        $this->dano_modifier = $dano_modifier;
        $this->chance_esquiva_modifier = $chance_esquiva_modifier;
        $this->is_equipped = $is_equipped;
    }

    public function getStats(): array
    {
        return ["velocidade" => $this->velocidade_modifier, "dano" => $this->dano_modifier, "chance_esquiva" => $this->chance_esquiva_modifier, "vida_max" => $this->vida_maxima_modifier];
    }

    public function getVelocidadeModifier(): int
    {
        return $this->velocidade_modifier;
    }
    public function getChanceEsquivaModifier(): int
    {
        return $this->chance_esquiva_modifier;
    }
    public function getDanoModifier(): int
    {
        return $this->dano_modifier;
    }
    public function getVidaMaximaModifier(): int
    {
        return $this->vida_maxima_modifier;
    }

    public function toggleEquipped(): void
    {
        $this->is_equipped = !$this->is_equipped;
    }

    public function getTipo(): string
    {
        $resposta = "";
        switch ($this->tipo) {
            case "mao_principal":
                $resposta = "Mão Principal";
                break;
            case "cabeca":
                $resposta = "Cabeça";
                break;
            case "peitoral":
                $resposta = "Peitoral";
                break;
            case "botas":
                $resposta = "Botas";
                break;
            case "mao_secundaria":
                $resposta = "Mão Secundaria";
                break;
        }
        return $resposta;
    }

    public function getHtml(): string
    {
        return "
        <tr>
            <td>".$this->nome."</td>
            <td>".$this->getTipo()."</td>
            <td>".$this->descricao."</td>
            <td>D:".$this->getDanoModifier()." V: ".$this->getVelocidadeModifier()." HP: ".$this->getVidaMaximaModifier(). " E: ".$this->getChanceEsquivaModifier()."</td>
            <td><a href='?item=".$this->id."'>Usar</a></td>
        </tr>
        ";
    }
}