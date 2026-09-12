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
        $this->is_new = true;
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

//        public function getHtml(): string
//        {
//            $acao = ($this->is_equipped) ? "Desequipar" : "Equipar";
//
//            return "
//            <tr>
//                <td>".$this->nome."</td>
//                <td>".$this->getTipo()."</td>
//                <td>Dano: ".$this->getDanoModifier()." Velocidade: ".$this->getVelocidadeModifier()." Vida Max.: ".$this->getVidaMaximaModifier(). " Esquiva: ".$this->getChanceEsquivaModifier()."</td>
//                <td><a href='?item=".$this->id."'>".$acao."</a></td>
//            </tr>
//            ";
//        }

    public function getHtml(): string
    {
        $acao = ($this->is_equipped) ? "Desequipar" : "Equipar";
        $novo = ($this->is_new) ? "<span class='newA'>*<span class='new'>New</span>*</span>" : "";
        return "
    <tr>
        <td>".$novo.$this->nome."</td>
        <td>".$this->getTipo()."</td>
        <td>
            <span>
                <img src='img/icons/dano_icon.png' alt='Dano' width='20'>
                ".$this->getDanoModifier()."
            </span>

            <span>
                <img src='img/icons/velocidade_icon.png' alt='Velocidade' width='20'>
                ".$this->getVelocidadeModifier()."
            </span>

            <span>
                <img src='img/icons/vida_icon.png' alt='Vida Máxima' width='20'>
                ".$this->getVidaMaximaModifier()."
            </span>

            <span>
                <img src='img/icons/esquiva_icon.png' alt='Esquiva' width='20'>
                ".$this->getChanceEsquivaModifier()."
            </span>
        </td>
        <td><a href='?item=".$this->id."'>".$acao."</a></td>
    </tr>
    ";
    }

}