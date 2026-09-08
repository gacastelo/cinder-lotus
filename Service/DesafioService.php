<?php
require_once "../Model/Desafio.php";
require_once "../Model/Dados.php";
require_once "../Service/AnimationService.php";
class DesafioService
{
    public static function fugir()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        AnimationService::limpar();
        unset($_SESSION["acoesDesafio"]);
        header("location: ../public/mapa.php");
        exit();
    }

    public static function getBackground(int $LevelID): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        return $_SESSION["fases"][$LevelID]->getLinkBackground();
    }

    private static function getTabelaLv0(): array
    {
        return [
            new Desafio(
                "Ponte Quebrada",
                8,
                "A ponte está quebrada. Você precisará atravessar seus restos para continuar.",
                "ponte_quebrada",
                "velocidade",
                "Correr pelos escombros",
                "Destruir o restante da estrutura",
                "Saltar sobre as falhas"
            ),
        ];
    }

    private static function getTabelaLv1(): array
    {
        return [
            new Desafio(
                "Ponte de Madeira",
                12,
                "A velha ponte range a cada passo. Atravesse sem deixar que ela desabe.",
                "ponte_madeira",
                "velocidade",
                "Correr pela ponte",
                "Pular a ponte",
                "Equilibrar-se na ponte"
            ),

            new Desafio(
                "Ponte de Pedra",
                14,
                "Uma antiga ponte de pedra bloqueia o caminho. O terreno é irregular e perigoso.",
                "ponte_pedra",
                "chance_esquiva",
                "Atravessar correndo rapidamente",
                "Forçar passagem pisando firme",
                "Desviar das pedras soltas"
            ),

            new Desafio(
                "Campo Aberto",
                10,
                "Um enorme campo se estende à sua frente. Algo se move entre a vegetação.",
                "campo",
                "velocidade",
                "Sprintar pelo campo",
                "Avançar cortando a vegetação",
                "Desviar das investidas na grama"
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
                "chance_esquiva",
                "Subir correndo antes que rolem pedras",
                "Empurrar os obstáculos do caminho",
                "Esquivar das pedras que caem"
            ),

            new Desafio(
                "Floresta Sombria",
                18,
                "A floresta é densa e quase não há luz entre as árvores. Você sente que está sendo observado.",
                "floresta",
                "dano",
                "Correr entre as árvores escuras",
                "Atacar a escuridão de frente",
                "Esquivar dos galhos baixos"
            ),

            new Desafio(
                "Parede Escalável",
                20,
                "Uma enorme parede bloqueia o caminho. Será necessário encontrar uma maneira de escalá-la.",
                "parede_escalavel",
                "velocidade",
                "Escalar o mais rápido possível",
                "Forçar o apoio socando a rocha",
                "Desviar de rachaduras na parede"
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
                "chance_esquiva",
                "Correr antes que a noite feche",
                "Avançar alertando qualquer ameaça",
                "Desviar de sombras na penumbra"
            ),

            new Desafio(
                "Montanha Noturna",
                22,
                "A trilha pela montanha tornou-se ainda mais perigosa depois do anoitecer.",
                "montanha_noite",
                "dano",
                "Subir às pressas no escuro",
                "Arrombar caminho pela rocha fria",
                "Esquivar de precipícios invisíveis"
            ),

            new Desafio(
                "Floresta Noturna",
                25,
                "A floresta está completamente escura. Criaturas se escondem entre as árvores.",
                "floresta_noite",
                "velocidade",
                "Disparar pela mata escura",
                "Atacar monstros à cegas",
                "Esquivar de emboscadas na escuridão"
            ),

            new Desafio(
                "Parede de Deslizamento",
                28,
                "A parede começa a desmoronar enquanto você tenta atravessá-la. Não há tempo para hesitar.",
                "parede_deslizamento",
                "chance_esquiva",
                "Escalar correndo do desabamento",
                "Segurar firme na rocha que cede",
                "Desviar dos escombros caindo"
            ),
        ];
    }

    public static function initDesafio(string $level): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        if (!isset($_SESSION["desafio"])){
            unset($_SESSION["acoesDesafio"]);
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