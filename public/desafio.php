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

if (isset($_GET["acao"])) {
    $resultado = DesafioService::desafiarDesafio($_SESSION["desafio"], $_GET["acao"]);

    if ($resultado) {
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

if (isset($_GET["resultado"])) {
    if ($_GET["resultado"] == "passou") {
        DesafioService::recompensar($_SESSION["fases"][$_SESSION["cenarioAtualId"]]->getDificuldade());
    } else {
        DesafioService::penalizar($_SESSION["fases"][$_SESSION["cenarioAtualId"]]->getDificuldade());
    }
}

$player = $_SESSION["player"];
$inventario = $player->getInventario();

DesafioService::initDesafio($_SESSION["fases"][$_SESSION["cenarioAtualId"]]->getDificuldade());

if (!isset($_SESSION["background"])) {
    $_SESSION["background"] = $_SESSION["desafio"]->getLinkBackground();
}
//var_dump($_SESSION["desafio"]);
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
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="resources/css/animacoes.css">

    <style>
        .batalha {
            background-image: url("<?= $_SESSION["background"] ?>");
        }

        #dado3d {
            width: 100%;
            height: 100%;
            position: absolute;
            display: none;
            z-index: 9998;
        }
    </style>
</head>

<body>

<main>
    <div id="dado3d"></div>
    <section class="batalha">
        <div id="descricao">
            <span><?= $_SESSION["desafio"]->getDescricao() ?></span>
        </div>

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
                if (!isset($_SESSION["acoesDesafio"])) {
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

                case "dado":
                    dado3d.style.display = "block";
                    dado3d.classList.add("esmaecer");
                    await esperarAnimacao(1000);

                    await animacaoDado(acao.resultado);

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

    const dado3d = document.getElementById("dado3d");

    async function animacaoDado(resultado) {
        bloquearBatalha(true);

        rolarDado(resultado)

        await esperarAnimacao(1500);
    }

    async function animacaoDerrota() {

        bloquearBatalha(true);

        // Faz o jogador cair
        personagemJogador.classList.add("animacao-derrota");

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

        const url = new URL(window.location.href);

        url.searchParams.set("resultado", "nahhh");

        window.location.href = url.toString();
    }

    async function animacaoVitoria() {

        bloquearBatalha(true);

        personagemJogador.classList.add("animacao-vitoria");

        await esperarAnimacao(1000);

        const tela = document.createElement("div");

        tela.classList.add("tela-vitoria");

        const texto = document.createElement("div");

        texto.classList.add("texto-vitoria");

        texto.textContent = "Sucesso!";

        tela.appendChild(texto);

        document.querySelector(".batalha").appendChild(tela);

        await esperarAnimacao(3000);

        const url = new URL(window.location.href);

        url.searchParams.set("resultado", "passou");

        window.location.href = url.toString();
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
    if (isset($_SESSION["acoes"])) {
        echo "executarAcoes(" . json_encode($_SESSION["acoes"]) . ")";
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
<!--Daqui pra baixo vai ser só o dado q eu fiquei fazendo (JS com ajuda de IA)-->
<script type="importmap">
    {
        "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js",
            "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"
        }
    }
</script>
<script type="module">

    import * as THREE from "three";

    import {
        GLTFLoader
    } from 'three/addons/loaders/GLTFLoader.js';


    // =====================================================
    // ORIENTAÇÕES
    // =====================================================

    const orientacoes = {

        1: {x: 29.3059, y: 31.4159, z: 0.0000},
        2: {x: 77.0282, y: 82.7014, z: 0.2600},
        3: {x: 83.3177, y: 114.1273, z: -8.4850},
        4: {x: 0.0500, y: 0.5600, z: 2.2000},
        5: {x: 20.4859, y: 36.6891, z: -4.0550},
        6: {x: 69.2082, y: 87.3946, z: 4.1500},
        7: {x: 27.3859, y: 37.6991, z: -3.1400},
        8: {x: 70.8750, y: 74.3782, z: -0.3100},
        9: {x: -10.3300, y: 3.1500, z: -2.5100},
        10: {x: -13.6100, y: 3.1500, z: -3.7500},
        11: {x: -10.5400, y: 3.1500, z: -3.7500},
        12: {x: -7.3300, y: 3.1500, z: -2.5100},
        13: {x: 36.1591, y: 42.9623, z: -0.3100},
        14: {x: 30.3859, y: 37.6991, z: -3.1400},
        15: {x: 34.5091, y: 43.4123, z: 4.1500},
        16: {x: 55.0018, y: 80.6714, z: -4.0550},
        17: {x: -3.2300, y: 0.5600, z: 2.2000},
        18: {x: 86.4177, y: 114.1273, z: -8.4850},
        19: {x: 36.1791, y: 45.0223, z: 0.3600},
        20: {x: 1.1, y: 0, z: 0}

    };


    // =====================================================
    // THREE.JS
    // =====================================================

    const container =
        document.getElementById("dado3d");


    const cena =
        new THREE.Scene();


    const camera =
        new THREE.PerspectiveCamera(
            45,
            container.clientWidth / container.clientHeight,
            0.1,
            100
        );


    camera.position.set(
        0,
        1.5,
        5
    );


    camera.lookAt(
        0,
        0,
        0
    );


    // =====================================================
    // RENDERER
    // =====================================================

    const renderer =
        new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });


    renderer.setSize(
        container.clientWidth,
        container.clientHeight
    );


    renderer.setPixelRatio(
        Math.min(window.devicePixelRatio, 2)
    );


    renderer.shadowMap.enabled = true;


    container.appendChild(
        renderer.domElement
    );


    // =====================================================
    // LUZ
    // =====================================================

    cena.add(
        new THREE.HemisphereLight(
            0xffffff,
            0x222233,
            2
        )
    );


    const luz =
        new THREE.DirectionalLight(
            0xffffff,
            4
        );


    luz.position.set(
        3,
        5,
        4
    );


    luz.castShadow = true;


    cena.add(luz);


    // =====================================================
    // DADO
    // =====================================================

    let dado = null;


    const loader =
        new GLTFLoader();


    loader.load(
        "../public/resources/3d/d20_dice_w20_wurfel_3d_model_free_1k.glb",

        function (gltf) {

            dado = gltf.scene;


            dado.scale.set(
                0.01,
                0.01,
                0.01
            );


            dado.traverse(function (obj) {

                if (obj.isMesh) {

                    obj.castShadow = true;
                    obj.receiveShadow = true;

                }

            });


            cena.add(dado);

        }
    );


    // =====================================================
    // ROLAR
    // =====================================================

    function rolarDado(resultado) {

        if (!dado) return;


        const orientacao =
            orientacoes[resultado];


        if (!orientacao) return;


        const inicio =
            performance.now();


        const duracao =
            2000;


        const inicioX =
            dado.rotation.x;

        const inicioY =
            dado.rotation.y;

        const inicioZ =
            dado.rotation.z;


        const destinoX =
            orientacao.x +
            Math.PI * 2 * 3;

        const destinoY =
            orientacao.y +
            Math.PI * 2 * 3;

        const destinoZ =
            orientacao.z;


        function animar(tempo) {

            const progresso =
                Math.min(
                    (tempo - inicio) / duracao,
                    1
                );


            const suavizado =
                1 - Math.pow(
                    1 - progresso,
                    4
                );


            dado.rotation.x =
                inicioX +
                (destinoX - inicioX) *
                suavizado;


            dado.rotation.y =
                inicioY +
                (destinoY - inicioY) *
                suavizado;


            dado.rotation.z =
                inicioZ +
                (destinoZ - inicioZ) *
                suavizado;


            if (progresso < 1) {

                requestAnimationFrame(
                    animar
                );

            }

        }


        requestAnimationFrame(
            animar
        );

    }


    // =====================================================
    // RENDER
    // =====================================================

    function renderizar() {

        requestAnimationFrame(
            renderizar
        );


        renderer.render(
            cena,
            camera
        );

    }


    renderizar();


    // =====================================================
    // RESPONSIVO
    // =====================================================

    window.addEventListener(
        "resize",
        function () {

            camera.aspect =
                container.clientWidth /
                container.clientHeight;


            camera.updateProjectionMatrix();


            renderer.setSize(
                container.clientWidth,
                container.clientHeight
            );

        }
    );

    window.rolarDado =
        rolarDado;

</script>
</body>

</html>