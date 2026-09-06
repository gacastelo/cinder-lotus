<?php
require "../Model/Fase.php";

class CenarioService
{
    public static function generateAllCenarios(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $cenarios = [
            1 => ["tipo" => "combate", "bloqueada" => false, "dificuldade" => 0],
            2 => ["tipo" => "desafio", "bloqueada" => true, "dificuldade" => 0]
        ];

        for ($i = 3; $i <= 11; $i++) {
            if (rand(0, 2) <= 1) {
                $tipo = "combate";
            } else {
                $tipo = "desafio";
            }
            $cenarios[$i] = ["tipo" => $tipo, "bloqueada" => true, "dificuldade" => (intdiv($i - 3, 3) + 1)];
        }
        $cenarios[12] = ["tipo" => "combate", "bloqueada" => true, "dificuldade" => 4];
        $_SESSION["cenarios"] = $cenarios;
    }

    public static function getCena($cenaID): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $tipo = $_SESSION["cenarios"][$cenaID]["tipo"];
        if ($tipo == "desafio") {
            header("Location: ../public/desafio.php");
        } else {
            header("Location: ../public/combate.php");
        }
        exit();
    }

    public static function unlockNextLevel($cenaID): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["cenarios"][$cenaID]["bloqueada"] = false;
        $caminhos = $_SESSION["fases"][$cenaID]->getConexoes();
        foreach ($caminhos as $caminho) {
            $_SESSION["fases"][$caminho]->unlock();
        }
    }

    public static function isUnlocked($cenaID): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        return !$_SESSION["cenarios"][$cenaID]["bloqueada"];
    }

    public static function definirCenario($cenaId): void
    {
        $_SESSION["cenarioAtualId"] = $cenaId;
    }

    public static function verifyCenario(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($_SESSION["cenarioAtualId"] != $_GET["cena"]) {
            header("Location: ../public/jogo.php");
            exit();
        }
    }

    public static function generateAllFases(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION["fases"] = [

            1 => new Fase(1, "Fase 1", 10, 75, [
                "direita" => 2
            ], false),

            2 => new Fase(2, "Fase 2", 22, 65, [
                "esquerda" => 1,
                "direita" => 3,
                "cima" => 4
            ]),

            3 => new Fase(3, "Fase 3", 35, 72, [
                "esquerda" => 2,
                "direita" => 5
            ]),

            4 => new Fase(4, "Fase 4", 30, 40, [
                "baixo" => 2,
                "direita" => 6
            ]),

            5 => new Fase(5, "Fase 5", 48, 65, [
                "esquerda" => 3,
                "direita" => 7
            ]),

            6 => new Fase(6, "Fase 6", 45, 30, [
                "esquerda" => 4,
                "direita" => 8
            ]),

            7 => new Fase(7, "Fase 7", 60, 75, [
                "esquerda" => 5,
                "direita" => 9,
                "cima" => 8
            ]),

            8 => new Fase(8, "Fase 8", 62, 40, [
                "esquerda" => 6,
                "baixo" => 7,
                "direita" => 10
            ]),

            9 => new Fase(9, "Fase 9", 73, 72, [
                "esquerda" => 7,
                "direita" => 11
            ]),

            10 => new Fase(10, "Fase 10", 75, 35, [
                "esquerda" => 8,
                "direita" => 12
            ]),

            11 => new Fase(11, "Fase 11", 87, 65, [
                "esquerda" => 9,
                "cima" => 12
            ]),

            12 => new Fase(12, "Chefe Final", 88, 30, [
                "esquerda" => 10,
                "baixo" => 11
            ])
        ];
    }
    public static function reset(): void
    {
        unset($_SESSION["cenarios"]);
    }
}