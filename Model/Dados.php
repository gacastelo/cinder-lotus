<?php

class Dados
{
    public static function rolarDados(): int
    {
        return rand(1, 20);
    }

    public static function testeCD($CD, $modificador = 0): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        $dado = Dados::rolarDados();
        $_SESSION["acoes"][] = ["tipo" => "dado", "resultado" => $dado];

        if ($dado == 20){
            return true;
        }

        if ($dado == 1){
            return false;
        }

        $resultado = $dado + $modificador;
        return $resultado >= $CD;
    }
}