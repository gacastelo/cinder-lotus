<?php

class Dados
{
    public static function rolarDados(): int
    {
        return rand(1, 20);
    }

    public static function testeCD($CD, $modificador = 0): bool
    {
        $resultado = Dados::rolarDados() + $modificador;
        return $resultado >= $CD;
    }
}