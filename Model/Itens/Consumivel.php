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
        $this->is_new = true;
    }

    public function getEfeito(): array
    {
        return $this->efeito;
    }

    public function getHtml($inventario = false): string
    {
        if ($inventario) {
            $novo = ($this->is_new) ? "<span class='newA'>*<span class='new'>New</span>*</span>" : "";
            return "
        <tr>
            <td>".$novo.$this->nome."</td>
            <td>" . $this->tipo . "</td>
            <td>" . $this->descricao . "</td>
            <td><a href='?item=" . $this->id . "'>Usar</a></td>
        </tr>
        ";
        }
        return parent::getHtml();
    }
}