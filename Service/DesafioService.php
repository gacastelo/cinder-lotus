<?php
require_once "../Model/Desafio.php";
require_once "../Model/Dados.php";
class DesafioService
{
    private static function getTabelaLv1(): array
    {
        return [
            new Desafio(
                "Ponte de Madeira",
                12,
                "A velha ponte range a cada passo. Atravesse sem deixar que ela desabe.",
                "ponte_madeira",
                "velocidade"
            ),

            new Desafio(
                "Ponte de Pedra",
                14,
                "Uma antiga ponte de pedra bloqueia o caminho. O terreno é irregular e perigoso.",
                "ponte_pedra",
                "chance_esquiva"
            ),

            new Desafio(
                "Campo Aberto",
                10,
                "Um enorme campo se estende à sua frente. Algo se move entre a vegetação.",
                "campo",
                "velocidade"
            ),
        ];
    }

    private static function getTabelaLv2(): array
    {
        return [
            new Desafio(
                "Trilha da Montanha",
                16,
                "O caminho sobe pela montanha. Pedras soltas tornam cada passo perigoso.",
                "montanha",
                "chance_esquiva"
            ),

            new Desafio(
                "Floresta Sombria",
                18,
                "A floresta é densa e quase não há luz entre as árvores. Você sente que está sendo observado.",
                "floresta",
                "dano"
            ),

            new Desafio(
                "Parede Escalável",
                20,
                "Uma enorme parede bloqueia o caminho. Será necessário encontrar uma maneira de escalá-la.",
                "parede_escalavel",
                "velocidade"
            ),
        ];
    }

    private static function getTabelaLv3(): array
    {
        return [
            new Desafio(
                "Campo ao Anoitecer",
                15,
                "A noite começa a cair sobre o campo. A escuridão dificulta enxergar o que vem pela frente.",
                "campo_noite",
                "chance_esquiva"
            ),

            new Desafio(
                "Montanha Noturna",
                22,
                "A trilha pela montanha tornou-se ainda mais perigosa depois do anoitecer.",
                "montanha_noite",
                "dano"
            ),

            new Desafio(
                "Floresta Noturna",
                25,
                "A floresta está completamente escura. Criaturas se escondem entre as árvores.",
                "floresta_noite",
                "velocidade"
            ),

            new Desafio(
                "Parede de Deslizamento",
                28,
                "A parede começa a desmoronar enquanto você tenta atravessá-la. Não há tempo para hesitar.",
                "parede_deslizamento",
                "chance_esquiva"
            ),
        ];
    }

    private static function getTabelaLv0(): array
    {
        return [
            new Desafio(
                "Ponte Quebrada",
                8,
                "A ponte está quebrada. Você precisará atravessar seus restos para continuar.",
                "ponte_quebrada",
                "velocidade"
            ),
        ];
    }

    public static function initDesafio(string $level)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        if (!isset($_SESSION["desafio"])){
            $desafio = DesafioService::gerarDesafio($level);
            $_SESSION["desafio"] = $desafio;
        }
    }

    public static function gerarDesafio(string $level = "1"): \Desafio
    {
        $get = "getTabelaLv". $level;
        $table = DesafioService::$get();
        return $table[array_rand($table)];
    }

    public static function desafiarDesafio(int $cd, string $bestAtribute, $selectedAtribute, $atributeValue): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        if ($bestAtribute == $selectedAtribute) {
            $atributeValue *= 1.2;
        }
        return Dados::testeCD($cd, $atributeValue);
    }
}