<?php

class BestiarioService
{
    public static function getBestiario(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $r = "";

        $dir = scandir("../public/img/inimigos");
        sort($dir);
        foreach ($dir as $inimigo) {
            if ($inimigo == "." || $inimigo == ".." || $inimigo == "cinder_lotus.gif") {
                continue;
            }

            $nome = ucwords(str_replace(["_", "base", ".gif"], [" ", "", ""], $inimigo));
            if (isset($_SESSION["bestiario"][str_replace([".gif"], "", $inimigo)])) {
                $encontrado = $_SESSION["bestiario"][str_replace([".gif"], "", $inimigo)]["encontrados"];
                $derrotado = $_SESSION["bestiario"][str_replace([".gif"], "", $inimigo)]["derrotados"];
                $status = "";
            }
                else {
                    $nome = preg_replace('/\S/', '?', $nome);
                    $encontrado = 0;
                    $derrotado = 0;
                    $status = "desconhecido";
                }

            $r .= "<div class='monstro'>
                <div class='blocker'><img src=./img/inimigos/$inimigo class='monstro-imagem $status' alt='$nome'></div>
                <div class='monstro-info'>
                    <span class='monstro-nome'>$nome</span>
                    <span>Encontrados: $encontrado</span>
                    <span>Derrotados: $derrotado</span>
                </div>
            </div>";
        }

        return $r;
    }

    public static function increaseEncontrados(string $bestiarioId): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION["bestiario"][$bestiarioId])) {
            $_SESSION["bestiario"][$bestiarioId] = ["encontrados" => 0, "derrotados" => 0];
        }

        $_SESSION["bestiario"][$bestiarioId]["encontrados"]++;
    }

    public static function increaseDerrotados(string $bestiarioId): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION["bestiario"][$bestiarioId])) {
            $_SESSION["bestiario"][$bestiarioId] = ["encontrados" => 0, "derrotados" => 0];
        }

        $_SESSION["bestiario"][$bestiarioId]["derrotados"]++;
    }

}