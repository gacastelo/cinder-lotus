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
    $resultado = DesafioService::desafiarDesafio($_SESSION["desafio"], $_GET["acao"]);

    if (!$resultado){
    $_SESSION["acoes"][] = ["tipo" => "resultado", "resultado" => "vitoria"];
    } else {
    $_SESSION["acoes"][] = ["tipo" => "resultado", "resultado" => "derrota"];
    }

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
//var_dump($_SESSION["acoes"]);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Desafio</title>

    <link rel="stylesheet" href="resources/css/desafio.css">
    <link rel="stylesheet" href="resources/css/animacoes.css">

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
                Ações
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

            <?php
                if (!isset($_SESSION["acoesDesafio"])){
                    $_SESSION["acoesDesafio"] = $_SESSION["desafio"]->getHtmlAcoes();
                }
                echo $_SESSION["acoesDesafio"];
            ?>


            </div>

            <div
                    class="itens"
                    id="itens"
            >

                <table>

                    <thead>

                    <tr>

                        <th>Item</th>

                        <th>Tipo</th>

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
        /* =========================================================
       [ANIMAÇÃO NOVA] - SISTEMA DE AÇÕES DE BATALHA
       ========================================================= */


    /*
        [ANIMAÇÃO NOVA]

        Pega os personagens que já existem no HTML.
    */

    const personagemJogador =
        document.querySelector(".jogador");


    const personagemInimigo =
        document.querySelector(".inimigo");


    /*
        [ANIMAÇÃO NOVA]

        Função para esperar determinado tempo.
    */

    function esperarAnimacao(tempo) {

        return new Promise(resolve => {

            setTimeout(resolve, tempo);

        });

    }


    async function executarAcoes(acoes) {

        /*
            [ANIMAÇÃO NOVA]

            Bloqueia os menus enquanto a animação acontece.
        */

        bloquearBatalha(true);


        for (const acao of acoes) {


            switch (acao.tipo) {


                /*
                    [ANIMAÇÃO NOVA]
                    ATAQUE
                */

                case "atacar":

                    await animacaoAtacar(
                        acao.quem,
                        acao.distancia ?? 80
                    );

                    break;



                /*
                    [ANIMAÇÃO NOVA]
                    DANO
                */

                case "dano":

                    await animacaoDano(
                        acao.alvo,
                        acao.valor
                    );

                    break;



                /*
                    [ANIMAÇÃO NOVA]
                    ESPERAR
                */

                case "esperar":

                    await esperarAnimacao(
                        acao.tempo ?? 500
                    );

                    break;



                /*
                    [ANIMAÇÃO NOVA]
                    MENSAGEM
                */

                case "mensagem":

                    alert(acao.texto);

                    break;

                case "resultado":

                    await esperarAnimacao(1000);

                    if (acao.resultado === "vitoria") {
                        await animacaoVitoria();
                        return;
                    }

                    if (acao.resultado === "derrota") {
                        await animacaoDerrota();
                        return;
                    }

                    break;
            }

        }


        /*
            [ANIMAÇÃO NOVA]

            Libera os menus novamente.
        */

        bloquearBatalha(false);

    }


    /*
        ========================================================
        [ANIMAÇÃO NOVA]
        ANIMAÇÃO DE ATAQUE
        ========================================================
    */

    async function animacaoAtacar(
        quem,
        distancia = 80
    ) {


        let personagem;


        /*
            [ANIMAÇÃO NOVA]

            Descobre qual personagem está atacando.
        */

        if (quem === "jogador") {

            personagem = personagemJogador;

        } else {

            personagem = personagemInimigo;

        }


        /*
            [ANIMAÇÃO NOVA]

            Jogador vai para a direita.

            Inimigo vai para a esquerda.
        */

        let movimento;


        if (quem === "jogador") {

            movimento = distancia;

        } else {

            movimento = -distancia;

        }


        /*
            [ANIMAÇÃO NOVA]

            Avança.
        */

        personagem.style.transform =
            `translateX(${movimento}px)`;


        await esperarAnimacao(200);


        /*
            [ANIMAÇÃO NOVA]

            Volta.
        */

        personagem.style.transform =
            "translateX(0px)";


        await esperarAnimacao(200);

    }
    
    async function animacaoDerrota() {

        bloquearBatalha(true);

        // Faz o jogador cair
        personagemJogador.classList.add("animacao-derrota");

        // Faz inimigo comemorar
        personagemInimigo.classList.add("animacao-vitoria");

        await esperarAnimacao(1500);

        // Cria a tela escura
        const tela = document.createElement("div");
        tela.classList.add("tela-derrota");

        document.querySelector(".batalha").appendChild(tela);

        const texto = document.createElement("div");

        texto.classList.add("texto-derrota");

        texto.textContent = "Falhou ;-;";

        tela.appendChild(texto);

        await esperarAnimacao(3000);

        window.location.href = "../public/mapa.php";
    }

    async function animacaoVitoria() {

        bloquearBatalha(true);

        // Jogador comemora
        personagemJogador.classList.add("animacao-vitoria");

        //Inimigo Cai
        personagemInimigo.classList.add("animacao-derrota");

        await esperarAnimacao(1000);

        // Cria a tela de vitória
        const tela = document.createElement("div");

        tela.classList.add("tela-vitoria");

        const texto = document.createElement("div");

        texto.classList.add("texto-vitoria");

        texto.textContent = "Sucesso!";

        tela.appendChild(texto);

        document.querySelector(".batalha").appendChild(tela);

        await esperarAnimacao(3000);

        window.location.href = "../public/mapa.php";
    }


    /*
        ========================================================
        [ANIMAÇÃO NOVA]
        BLOQUEAR MENU DURANTE ANIMAÇÃO
        ========================================================
    */

    function bloquearBatalha(bloquear) {


        const botoes =
            document.querySelectorAll(
                ".menu button, .acoes div, .itens button"
            );


        botoes.forEach(botao => {

            botao.style.pointerEvents =
                bloquear ? "none" : "auto";


            botao.style.opacity =
                bloquear ? "0.6" : "1";

        });

    }

    <?php
        if (isset($_SESSION["acoes"])){
            echo "executarAcoes(".json_encode($_SESSION["acoes"]).")";
        }
    ?>
</script>
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