<?php
require_once "../Model/Entidades/Player.php";
require_once "../Model/Entidades/Inimigo.php";
require_once "../Model/Itens/Equipamento.php";
require_once "../Model/Itens/Consumivel.php";
require_once "../Service/ConsumivelService.php";
require_once "../Service/DesafioService.php";
require_once "../Model/Fase.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["consumivelService"])) {
    $_SESSION["consumivelService"] = new ConsumivelService();
}

if (isset($_GET["item"])) {
    $_SESSION["player"]->useItem($_GET["item"]);
    header("Location: desafio.php");
    exit();
}

if (isset($_GET["acao"])){
    header("Location: desafio.php");
    exit();
}

if (isset($_GET["fugiu"])) {
    DesafioService::fugir();
}

$player = $_SESSION["player"];
$inventario = $player->getInventario();

DesafioService::initDesafio($_SESSION["fases"][$_SESSION["cenarioAtualId"]]->getDificuldade());

if (!isset($_SESSION["background"])){
    $_SESSION["background"] = $_SESSION["desafio"]->getLinkBackground();
}

//var_dump($_SESSION["cenarios"][$_SESSION["cenarioAtualId"]]["dificuldade"]);
//var_dump($player);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Desafio</title>

    <link rel="stylesheet" href="resources/css/desafio.css">
    <style>
        .batalha{
            background-image: url("<?= $_SESSION["background"] ?>");
        }
    </style>
</head>

<body>

<main>

    <section class="batalha">

        <img
                class="inimigo"
                src="img/default_hero.gif"
                alt="Inimigo"
        >

        <img
                class="jogador"
                src="img/default_hero.gif"
                alt="Jogador"
        >
    </section>

    <section class="menu">

        <div class="opcoes">

            <button
                    id="btnAcoes"
                    class="ativo"
                    onclick="mostrarAcoes()"
            >
                Açoes
            </button>


            <button
                    id="btnItens"
                    onclick="mostrarItens()"
            >
                Itens
            </button>

            <button
                    id="btnFugir"
                    onclick="fugir()"
            >
                Fugir
            </button>

        </div>

        <div class="conteudo">

            <div
                    class="acoes"
                    id="acoes"
            >

                <div onclick="usarAcao('Acao1')">
                    <span>Ação 1</span>
                </div>


            </div>

            <div
                    class="itens"
                    id="itens"
            >

                <table>

                    <thead>

                    <tr>

                        <th>Item</th>

                        <th>Descrição</th>

                        <th>Ação</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    foreach ($inventario as $consumivel) {

                        if ($consumivel instanceof Consumivel) {

                            echo $consumivel->getHtml();

                        }

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</main>


<script>


    const acoes =
        document.getElementById("acoes");


    const itens =
        document.getElementById("itens");


    const btnAcoes =
        document.getElementById("btnAcoes");


    const btnItens =
        document.getElementById("btnItens");

    function mostrarAcoes() {

        acoes.style.display = "grid";

        itens.style.display = "none";


        btnAcoes.classList.add("ativo");

        btnItens.classList.remove("ativo");

    }


    function mostrarItens() {

        acoes.style.display = "none";

        itens.style.display = "block";


        btnItens.classList.add("ativo");

        btnAcoes.classList.remove("ativo");


    }

    function usarAcao(nomeAcao) {

        alert(
            "<?=$_SESSION["player"]->getAtribute("nome") ?> usou: " + nomeAcao
        );

        const url = new URL(window.location.href);

        url.searchParams.set("acao", nomeAcao);

        window.location.href = url.toString();

    }

    function usarItem(nomeItem) {

        alert(
            "<?=$_SESSION["player"]->getAtribute("nome") ?> usou: " + nomeItem
        );

    }

    function fugir() {

        btnFugir.classList.add("ativo");

        btnAcoes.classList.remove("ativo");

        btnItens.classList.remove("ativo");

        const url = new URL(window.location.href);

        url.searchParams.set("fugiu", "true");

        window.location.href = url.toString();

    }
</script>

</body>

</html>