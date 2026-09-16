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

    public static function increaseDesafiosFalhos():void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseDesafiosFalhos();
    }

    public static function increaseDanoCausado(int $value): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseDanoCausado($value);
    }

    public static function increaseDanoSofrido(int $value): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["estatisticas"]->increaseDanoSofrido($value);
    }

    public static function setTempoConclusao(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $tempoConclusao = (int) (microtime(true) * 1000);

        $tempoInicial = $_SESSION["tempoInicial"];

        $tempoDeJogo = $tempoConclusao - $tempoInicial;

        $_SESSION["estatisticas"]->setTempoConclusao($tempoDeJogo);
    }

    public static function getAtualEstatisticaValues(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        $nome = $_SESSION["estatisticas"]->getNome();
        $ataquesTotais = $_SESSION["estatisticas"]->getAtaquesTotais();
        $ataquesAcertados = $_SESSION["estatisticas"]->getAtaquesAcertados();
        $itensConsumidos = $_SESSION["estatisticas"]->getItensConsumidos();
        $combatesGanhos = $_SESSION["estatisticas"]->getCombatesGanhos();
        $combatesFugas = $_SESSION["estatisticas"]->getCombatesFugas();
        $desafiosSuperados = $_SESSION["estatisticas"]->getDesafiosSuperados();
        $desafiosFugas = $_SESSION["estatisticas"]->getDesafiosFugas();
        $desafiosFalhos = $_SESSION["estatisticas"]->getDesafiosFalhos();
        $danoCausado = $_SESSION["estatisticas"]->getDanoCausado();
        $danoSofrido = $_SESSION["estatisticas"]->getDanoSofrido();
        $tempoConclusao = $_SESSION["estatisticas"]->getTempoConclusao();

        return [$nome, $ataquesTotais, $ataquesAcertados, $itensConsumidos,
            $combatesGanhos, $combatesFugas, $desafiosSuperados,
            $desafiosFugas, $desafiosFalhos, $danoCausado, $danoSofrido, $tempoConclusao];
    }
}