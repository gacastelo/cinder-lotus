<?php

require_once "../AbsModel/AbsItem.php";
require_once "../Model/Itens/Consumivel.php";
class ConsumivelService
{
    private function getTabelaLv1(): array
    {
        return [
            new Consumivel("Poçao de Cura Mínima", "Cura 10 de vida", ["metodo" => "heal", "args" => [10]]),
            new Consumivel("Poçao de Cura Menor", "Cura 25 de vida", ["metodo" => "heal", "args" => [25]]),
            new Consumivel("Poçao de Dano", "Aumenta seu dano em 10", ["metodo" => "buff", "args" => ["dano", 10, 5]]),
            new Consumivel("Poçao de Velocidade", "Aumenta sua velocidade em 5", ["metodo" => "buff", "args" => ["velocidade", 5, 5]]),
            new Consumivel("Poçao de Esquiva", "Aumenta sua chance de esquiva em 5%", ["metodo" => "buff", "args" => ["chance_esquiva", 5, 5]])
        ];
    }
    private function getTabelaLv2(): array
    {
        return [
            new Consumivel("Poçao de Cura", "Cura 35 de vida", ["metodo" => "heal", "args" => [35]]),
            new Consumivel("Poçao de Cura Maior", "Cura 50 de vida", ["metodo" => "heal", "args" => [50]]),
            new Consumivel("Poçao de Dano Maior", "Aumenta seu dano em 20", ["metodo" => "buff", "args" => ["dano", 20, 5]]),
            new Consumivel("Poçao de Velocidade Maior", "Aumenta sua velocidade em 10", ["metodo" => "buff", "args" => ["velocidade", 10, 5]]),
            new Consumivel("Poçao de Esquiva Maior", "Aumenta sua chance de esquiva em 10%", ["metodo" => "buff", "args" => ["chance_esquiva", 10, 5]])
        ];
    }
    private function getTabelaLv3(): array
    {
        return [
            new Consumivel("Poçao de Cura Superior", "Cura 75 de vida", ["metodo" => "heal", "args" => [75]]),
            new Consumivel("Poçao de Cura Suprema", "Cura 100 de vida", ["metodo" => "heal", "args" => [100]]),
            new Consumivel("Poçao de Dano Suprema", "Aumenta seu dano em 30", ["metodo" => "buff", "args" => ["dano", 30, 5]]),
            new Consumivel("Poçao de Velocidade Suprema", "Aumenta sua velocidade em 15", ["metodo" => "buff", "args" => ["velocidade", 15, 5]]),
            new Consumivel("Poçao de Esquiva Suprema", "Aumenta sua chance de esquiva em 15%", ["metodo" => "buff", "args" => ["chance_esquiva", 15, 5]])
        ];
    }

    public function gerarConsumivel(string $level): \Consumivel
    {
        if ($level != "1" || $level != "2" || $level != "3") {
            $level = strval(rand(1,3));
        }

        $get = "getTabelaLv". $level;
        $table = $this->$get();
        return $table[array_rand($table)];
    }
}