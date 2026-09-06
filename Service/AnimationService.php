<?php
class AnimationService
{
    public static function acaoMessage(string $texto): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "mensagem", "texto" => $texto];
    }

    public static function acaoAtacar(string $quem): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "atacar", "quem" => $quem];
    }

    public static function acaoDano(string $alvo, int $valor): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "dano", "alvo" => $alvo, "valor" => $valor];
    }

    public static function acaoEsperar(int $ms): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "esperar", "tempo" => $ms];
    }

    public static function limpar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"] = [];
        unset($_SESSION["background"]);
    }

    public static function derrota(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "resultado", "resultado" => "derrota"];
        unset($_SESSION["background"]);
    }

    public static function vitoria(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION["acoes"][] = ["tipo" => "resultado", "resultado" => "vitoria"];
        unset($_SESSION["background"]);
    }
}