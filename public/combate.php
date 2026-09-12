<?php
require_once "../Model/Entidades/Player.php";
require_once "../Model/Entidades/Inimigo.php";
require_once "../Model/Itens/Equipamento.php";
require_once "../Model/Itens/Consumivel.php";
require_once "../Service/ConsumivelService.php";
require_once "../Service/CombateService.php";
require_once "../Model/Fase.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$player = $_SESSION["player"];

if (!isset($_SESSION["consumivelService"])) {
    $_SESSION["consumivelService"] = new ConsumivelService();
}

if (!isset($_SESSION["combateService"])) {
    $_SESSION["combateService"] = new CombateService();
}

if (isset($_GET["item"])) {
    $_SESSION["player"]->useItem($_GET["item"]);
}

if (isset($_GET["item"]) || isset($_GET["ataque"])) {
    $_SESSION["combateService"]->passarTurno();
}

if (isset($_GET["fugiu"])) {
    $_SESSION["combateService"]->Fugir();
}

if (!isset($_SESSION["acoes"])) {
    $_SESSION["acoes"] = [];
}

if (!isset($_SESSION["background"])){
    $_SESSION["background"] = CombateService::getBackground();
}


$_SESSION["combateService"]->initCombate($_SESSION["fases"][$_SESSION["cenarioAtualId"]]->getDificuldade());
$inventario = $player->getInventario();

//var_dump($_SESSION["cenarios"][$_SESSION["cenarioAtualId"]]["dificuldade"]);
//var_dump($player);
//var_dump(($player->getDano()));
//var_dump($_SESSION["inimigo"]);
//var_dump($_SESSION["cenarios"][$_SESSION["cenarioAtualId"]]);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Batalha</title>

    <link rel="stylesheet" href="resources/css/animacoes.css">
    <link rel="stylesheet" href="resources/css/combate.css">
    <style>
        .batalha {
            background-image: url("<?= $_SESSION["background"] ?>");
        }
        <?php
            if ($_SESSION["inimigo"]->getNome() == "Cinder Lotus"){
                echo ".inimigo {
                  top: 22%;
                  right: 5%;
                  width: 25%;
                  height: 80%;
                }";
            }
        ?>
    </style>
</head>

<body>

<main>

    <section class="batalha">

        <img
                class="inimigo"
                src="<?=$_SESSION["inimigo"]->getLinkImagem()?>"
                alt="<?=$_SESSION["inimigo"]->getNome()?>"
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
                    id="btnAtaques"
                    class="ativo"
                    onclick="mostrarAtaques()"
            >
                Ataques
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
                    class="ataques"
                    id="ataques"
            >

                <div onclick="usarAcao('Ataque1')">
                    <span>Ataque 1</span>
                </div>


                <div onclick="usarAcao('Ataque2')">
                    <span>Ataque 2</span>
                </div>


                <div onclick="usarAcao('Ataque3')">
                    <span>Ataque 3</span>
                </div>


                <div onclick="usarAcao('Ataque4')">
                    <span>Ataque 4</span>
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


    const ataques =
        document.getElementById("ataques");


    const itens =
        document.getElementById("itens");


    const btnAtaques =
        document.getElementById("btnAtaques");


    const btnItens =
        document.getElementById("btnItens");


    const btnFugir =
        document.getElementById("btnFugir");


    function mostrarAtaques() {

        ataques.style.display = "grid";

        itens.style.display = "none";


        btnAtaques.classList.add("ativo");

        btnItens.classList.remove("ativo");

        btnFugir.classList.remove("ativo");

    }


    function mostrarItens() {

        ataques.style.display = "none";

        itens.style.display = "block";


        btnItens.classList.add("ativo");

        btnAtaques.classList.remove("ativo");

        btnFugir.classList.remove("ativo");

    }


    function fugir() {

        btnFugir.classList.add("ativo");

        btnAtaques.classList.remove("ativo");

        btnItens.classList.remove("ativo");

        const url = new URL(window.location.href);

        url.searchParams.set("fugiu", "true");

        window.location.href = url.toString();

    }


    function usarAcao(nomeAtaque) {

        alert(
            "<?=$_SESSION["player"]->getAtribute("nome") ?> usou: " + nomeAtaque
        );

        const url = new URL(window.location.href);

        url.searchParams.set("ataque", nomeAtaque);

        window.location.href = url.toString();

    }


    function usarItem(nomeItem) {

        alert(
            "<?=$_SESSION["player"]->getAtribute("nome") ?> usou: " + nomeItem
        );

    }


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


    /*
        ========================================================
        [ANIMAÇÃO NOVA]
        ANIMAÇÃO DE DANO
        ========================================================
    */

    async function animacaoDano(
        alvo,
        valor = null
    ) {


        let personagem;


        /*
            [ANIMAÇÃO NOVA]

            Descobre quem sofreu dano.
        */

        if (alvo === "jogador") {

            personagem = personagemJogador;

        } else {

            personagem = personagemInimigo;

        }


        /*
            [ANIMAÇÃO NOVA]

            Pisca.
        */

        personagem.classList.add(
            "animacao-dano"
        );

        /*
            [ANIMAÇÃO NOVA]

            Mostra o valor do dano.
        */

        if (valor !== null) {

            mostrarNumeroDano(
                personagem,
                valor
            );

        }


        /*
            [ANIMAÇÃO NOVA]

            Espera terminar.
        */

        await esperarAnimacao(600);


        /*
            [ANIMAÇÃO NOVA]

            Remove as animações.
        */

        personagem.classList.remove(
            "animacao-dano"
        );

        personagem.classList.remove(
            "animacao-tremer"
        );


        await esperarAnimacao(100);

    }


    /*
        ========================================================
        [ANIMAÇÃO NOVA]
        NÚMERO DE DANO
        ========================================================
    */

    function mostrarNumeroDano(
        personagem,
        valor
    ) {


        /*
            [ANIMAÇÃO NOVA]

            Cria o elemento do dano.
        */

        const numero =
            document.createElement("div");


        numero.classList.add(
            "numero-dano"
        );


        numero.textContent =
            "-" + valor;


        /*
            [ANIMAÇÃO NOVA]

            Descobre as posições.
        */

        const posicao =
            personagem.getBoundingClientRect();


        const batalha =
            document.querySelector(".batalha");


        const posicaoBatalha =
            batalha.getBoundingClientRect();


        /*
            [ANIMAÇÃO NOVA]

            Posiciona o dano sobre o personagem.
        */

        numero.style.left =
            (
                posicao.left
                -
                posicaoBatalha.left
                +
                posicao.width / 2
            ) + "px";


        numero.style.top =
            (
                posicao.top
                -
                posicaoBatalha.top
                +
                posicao.height / 3
            ) + "px";


        batalha.appendChild(numero);
        /*
            [ANIMAÇÃO NOVA]

            Remove depois de 800ms.
        */

        setTimeout(() => {

            numero.remove();

        }, 800);

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

        await esperarAnimacao(3000);

        window.location.href = "../public/derrota.php";
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

        texto.textContent = "VITÓRIA!";

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
                ".menu button, .ataques div, .itens button"
            );


        botoes.forEach(botao => {

            botao.style.pointerEvents =
                bloquear ? "none" : "auto";


            botao.style.opacity =
                bloquear ? "0.6" : "1";

        });

    }
    executarAcoes(<?= json_encode($_SESSION["acoes"]) ?>)

</script>

</body>

</html>