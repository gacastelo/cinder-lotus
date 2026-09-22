<?php
require_once "../Model/Desafio.php";
require_once "../Model/Dados.php";
require_once "../Service/AnimationService.php";
require_once "../Service/EstatisticaService.php";

class DesafioService
{
    public static function fugir()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        self::limpar();
        EstatisticaService::increaseDesafiosFugas();
        self::enviarMapa();
    }

    public static function limpar():void
    {
        AnimationService::limpar();
        unset($_SESSION["acoesDesafio"]);
        unset($_SESSION["desafio"]);
    }

    public static function getBackground(int $LevelID): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return $_SESSION["fases"][$LevelID]->getLinkBackground();
    }

    private static function getTabelaLv0(): array
    {
        return [
            new Desafio(
                "Ponte Quebrada",
                    8,
                "A ponte está quebrada. Você precisará atravessar seus restos para continuar.",
                "img/cenarios/cena_desafio_ponte.png",
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
                "img/cenarios/cena_desafio_ponte_noite.png",
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
                "./img/cenarios/cena_campo_dia.png",
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
                "./img/cenarios/cena_montanha_dia.png",
                "chance_esquiva",
                "Subir correndo antes que rolem pedras",
                "Empurrar os obstáculos do caminho",
                "Esquivar das pedras que caem"
            ),

            new Desafio(
                "Floresta Sombria",
                18,
                "A floresta é densa e quase não há luz entre as árvores. Você sente que está sendo observado.",
                "./img/cenarios/cena_floresta_dia.png",
                "dano",
                "Correr entre as árvores escuras",
                "Atacar a escuridão de frente",
                "Esquivar dos galhos baixos"
            ),

            new Desafio(
                "Parede Escalável",
                20,
                "Uma enorme parede bloqueia o caminho. Será necessário encontrar uma maneira de escalá-la.",
                "./img/cenarios/cena_desafio_escalada.png",
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
                "./img/cenarios/cena_campo_noite.png",
                "chance_esquiva",
                "Correr antes que a noite feche",
                "Avançar alertando qualquer ameaça",
                "Desviar de sombras na penumbra"
            ),

            new Desafio(
                "Montanha Noturna",
                22,
                "A trilha pela montanha tornou-se ainda mais perigosa depois do anoitecer.",
                "./img/cenarios/cena_montanha_noite.png",
                "dano",
                "Subir às pressas no escuro",
                "Arrombar caminho pela rocha fria",
                "Esquivar de precipícios invisíveis"
            ),

            new Desafio(
                "Floresta Noturna",
                25,
                "A floresta está completamente escura. Criaturas se escondem entre as árvores.",
                "./img/cenarios/cena_floresta_noite.png",
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION["desafio"])) {
            unset($_SESSION["acoesDesafio"]);
            $desafio = DesafioService::gerarDesafio($level);
            $_SESSION["desafio"] = $desafio;
        }
    }

    public static function gerarDesafio(string $level = "1"): \Desafio
    {
        $get = "getTabelaLv" . $level;
        $table = DesafioService::$get();
        return $table[array_rand($table)];
    }

    public static function desafiarDesafio(Desafio $desafio, string $selectedAtribute): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        switch ($selectedAtribute) {
            case "AcaoE":
                $atribute = $_SESSION["player"]->getChanceEsquiva();
                break;
            case "AcaoD":
                $atribute = $_SESSION["player"]->getDano();
                break;
            case "AcaoV":
                $atribute = $_SESSION["player"]->getVelocidade();
                break;
            default:
                return false;
        }

        return self::processarDesafio($desafio->getCd(), $desafio->getBestAtribute(), $selectedAtribute, $atribute);
    }


    private static function processarDesafio(int $cd, string $bestAtribute, $selectedAtribute, $atributeValue): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($bestAtribute == $selectedAtribute) {
            $atributeValue *= 1.2;
        }
        return Dados::testeCD($cd, $atributeValue);
    }

    private static function enviarMapa(): void
    {
        self::limpar();
        header("Location: mapa.php");
        exit();
    }

    private static function getTVD($level): array
    {
        $types = ["dano", "velocidade", "chance_esquiva", "vida_max"];
        $type = $types[array_rand($types)];

        $value = rand(1, ($level * 2)) * 2;

        if ($level == 3) {
            $duration = -1;
        } else {
            $duration = $level * 3;
        }
        return [$type, $value, $duration];
    }

    public static function penalizar(int $level): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $tvd = self::getTVD($level);
        $_SESSION["player"]->debuff($tvd[0], -$tvd[1], $tvd[2]);
        self::enviarMapa();
    }

    public static function recompensar(int $level): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION["player"]->buff(...self::getTVD($level));
        EstatisticaService::increaseDesafiosSuperados();
        self::enviarMapa();
    }
}