<?php

require_once '../Model/Estatistica.php';

class EstatisticaService
{
    public static function initEstatisticas(string $nome): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"] = new Estatistica($nome);
    }

    public static function increaseAtaquesTotais(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseAtaquesTotais();
    }

    public static function increaseAtaquesAcertados(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseAtaquesAcertados();
    }

    public static function increaseItensConsumidos(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseItensConsumidos();
    }

    public static function increaseCombatesGanhos(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseCombatesGanhos();
    }

    public static function increaseCombatesFugas(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseCombatesFugas();
    }

    public static function increaseDesafiosSuperados(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseDesafiosSuperados();
    }

    public static function increaseDesafiosFugas(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseDesafiosFugas();
    }

    public static function setTempoConclusao(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $tempoConclusao = new DateTime();

        $tempoInicial = $_SESSION["tempoInicial"];

        $tempoDeJogo = $tempoInicial->diff($tempoConclusao);

        $_SESSION["estatisticas"]->setTempoConclusao($tempoDeJogo);
    }
}