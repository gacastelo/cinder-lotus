<?php
require_once "../Model/Itens/Equipamento.php";
class EquipamentoService
{

    private function getTabelaLv1(): array
    {
        return [
            new Equipamento(
                "Capuz do Aprendiz",
                "cabeca",
                "Um simples capuz que oferece uma pequena proteção.",
                10, 0, 0, 0
            ),
            new Equipamento(
                "Peitoral de Couro",
                "peitoral",
                "Armadura leve feita de couro resistente.",
                25, 0, 0, 0
            ),
            new Equipamento(
                "Botas Leves",
                "botas",
                "Botas confortáveis que facilitam seus movimentos.",
                0, 3, 0, 0
            ),
            new Equipamento(
                "Espada Curta",
                "mao_principal",
                "Uma espada simples, porém confiável.",
                0, 0, 10, 0
            ),
            new Equipamento(
                "Broquel de Madeira",
                "mao_secundaria",
                "Um pequeno escudo de madeira capaz de ajudar na defesa.",
                5, 0, 0, 3
            )
        ];
    }

    private function getTabelaLv2(): array
    {
        return [
            new Equipamento(
                "Elmo de Ferro",
                "cabeca",
                "Um elmo resistente que protege bem a cabeça.",
                20, 0, 0, 2
            ),
            new Equipamento(
                "Peitoral Reforçado",
                "peitoral",
                "Uma armadura reforçada que oferece excelente proteção.",
                40, 0, 0, 0
            ),
            new Equipamento(
                "Botas do Vento",
                "botas",
                "Botas leves encantadas com a força dos ventos.",
                0, 6, 0, 3
            ),
            new Equipamento(
                "Espada Longa",
                "mao_principal",
                "Uma espada pesada capaz de causar grandes ferimentos.",
                0, -1, 20, 0
            ),
            new Equipamento(
                "Escudo de Ferro",
                "mao_secundaria",
                "Um escudo sólido que protege seu portador contra ataques.",
                20, -1, 0, 5
            )
        ];
    }

    private function getTabelaLv3(): array
    {
        return [
            new Equipamento(
                "Coroa do Campeão",
                "cabeca",
                "Uma coroa lendária que fortalece seu portador.",
                35, 1, 8, 5
            ),
            new Equipamento(
                "Armadura de Titã",
                "peitoral",
                "Uma armadura extremamente pesada e resistente.",
                60, -3, 10, 0
            ),
            new Equipamento(
                "Botas do Relâmpago",
                "botas",
                "Botas imbuídas com energia elétrica, aumentando drasticamente sua velocidade.",
                0, 10, 0, 8
            ),
            new Equipamento(
                "Espada GPC",
                "mao_principal",
                "Espada Sigma cujo o sol onsuiu aquela cuja a aura foi roubada de todos ao céu poente.",
                0, 0, 35, 0
            ),
            new Equipamento(
                "Escudo do Guardião",
                "mao_secundaria",
                "Um escudo lendário capaz de proteger seu portador dos ataques mais poderosos.",
                40, -2, 5, 10
            )
        ];
    }


    public function gerarEquipamento(string $level): \Equipamento
    {
        if ($level != "1" || $level != "2" || $level != "3") {
            $level = strval(rand(1,3));
        }

        $get = "getTabelaLv". $level;
        $table = $this->$get();
        return $table[array_rand($table)];
    }
}