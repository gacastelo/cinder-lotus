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

if (!isset($_SESSION["background"])) {
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
    <link rel="stylesheet" href="resources/css/tutorial.css">
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
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
    <div id='tutorial'>
        <span id='tutorial-message'></span>
    </div>
    <div id="tutorial-pergunta">
        <div class="tutorial-caixa">
            <span>Deseja fazer o tutorial?</span>

            <div class="tutorial-botoes">
                <button onclick="iniciarTutorial()">Sim</button>
                <button onclick="fecharTutorial()">Não</button>
            </div>
        </div>
    </div>
    <section class="batalha">

        <img
                class="inimigo"
                src="<?= $_SESSION["inimigo"]->getLinkImagem() ?>"
                alt="<?= $_SESSION["inimigo"]->getNome() ?>"
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
                    class="ataques"
                    id="ataques"
            >

                <div onclick="usarAcao('Ataque')">
                    <span>Atacar</span>
                </div>


                <div onclick="usarAcao('AtaqueForte')" id="ataqueForte">
                    <span>Atacar Forte</span>
                    <span class="cooldown"><?= $player->getStrongAttackCooldown() ?></span>
                </div>


                <div onclick="usarAcao('Defender')" id="defesa">
                    <span>Defender</span>
                    <span class="cooldown"><?= $player->getDefenderCooldown() ?></span>
                </div>


                <div onclick="usarAcao('Chorar')">
                    <span>Chorar</span>
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
    const botaoAtaqueForte = document.getElementById("ataqueForte")
    const botaoDefesa = document.getElementById("defesa")
    bloquearAcao(botaoAtaqueForte, <?= json_encode($player->getStrongAttackCooldown() != 0) ?>)
    bloquearAcao(botaoDefesa, <?= json_encode($player->getDefenderCooldown() != 0) ?>)

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

        const url = new URL(window.location.href);

        url.searchParams.set("ataque", nomeAtaque);

        window.location.href = url.toString();

    }


    function usarItem(nomeItem) {
        console.log(nomeItem)
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

                case "esquiva":

                    await animacaoEsquiva(
                        acao.alvo
                    );

                    break;

                case "chorar":

                    await animacaoChoro(
                        acao.alvo
                    );

                    break;

                case "defender":

                    await animacaoDefesa(
                        acao.alvo
                    );

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

        personagem.classList.add(
            "animacao-tremer"
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

    async function animacaoEsquiva(alvo) {

        let personagem;

        if (alvo === "jogador") {

            personagem = personagemJogador;

        } else {

            personagem = personagemInimigo;

        }

        personagem.classList.add(
            "animacao-esquiva"
        );

        mostrarNumeroDano(
            personagem,
            "Esquivou"
        );


        await esperarAnimacao(600);

        personagem.classList.remove(
            "animacao-esquiva"
        );

        await esperarAnimacao(100);

    }

    async function animacaoChoro(alvo) {
        let personagem;

        if (alvo === "jogador") {
            personagem = personagemJogador;
        } else {
            personagem = personagemInimigo;
        }

        personagem.classList.add(
            "animacao-chorar"
        );
        await esperarAnimacao(3000);

        personagem.classList.remove(
            "animacao-chorar"
        );

        await esperarAnimacao(100);
    }


    async function animacaoDefesa(alvo) {
        let personagem;

        if (alvo === "jogador") {
            personagem = personagemJogador;
        } else {
            personagem = personagemInimigo;
        }

        personagem.classList.add(
            "animacao-defender"
        );

        await esperarAnimacao(600);

        personagem.classList.remove(
            "animacao-defender"
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

        if (valor === "Esquivou") {
            numero.textContent = "*" + valor + "*";
        } else {
            numero.textContent =
                "-" + valor;
        }

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

        const texto = document.createElement("div");

        texto.classList.add("texto-derrota");

        texto.textContent = "Derrota";

        tela.appendChild(texto);

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
                ".menu button, .ataques div, .itens button, td a"
            );


        botoes.forEach(botao => {

            botao.style.pointerEvents =
                bloquear ? "none" : "auto";


            botao.style.opacity =
                bloquear ? "0.6" : "1";

        });
        let table = document.getElementById("itens")
            table.style.opacity =
                bloquear ? "0.6" : "1";
        if (!bloquear) {
            bloquearAcao(botaoAtaqueForte, <?= json_encode($player->getStrongAttackCooldown() != 0) ?>)
            bloquearAcao(botaoDefesa, <?= json_encode($player->getDefenderCooldown() != 0) ?>)
        }

    }

    function bloquearAcao(botao, bloquear) {
        botao.style.pointerEvents =
            bloquear ? "none" : "auto";


        botao.style.opacity =
            bloquear ? "0.6" : "1";
        let cooldown = botao.querySelector(".cooldown");
        cooldown.style.display =
            bloquear ? "flex" : "none";
    }

    executarAcoes(<?= json_encode($_SESSION["acoes"]) ?>)

</script>
<script>
    const divTutorial = document.getElementById("tutorial")
    const textTutorial = document.getElementById("tutorial-message")


    const tutorialPergunta = document.getElementById("tutorial-pergunta");

    function perguntarTutorial() {
        tutorialPergunta.style.display = "flex";
    }

    function fecharTutorial() {
        tutorialPergunta.style.display = "none";
    }

    async function iniciarTutorial() {
        tutorialPergunta.style.display = "none";
        await initTutorial();
    }

    if (isTutorial()){
        perguntarTutorial();
    }

    function isTutorial(){
        return <?= json_encode($_SESSION["cenarioAtualId"] == 1 && !isset($_SESSION["TutorialIniciado"])); $_SESSION["TutorialIniciado"] = true; ?>;
    }


    async function changeTextToBatalhaTutorial(){
        bloquearBatalha(true);
        divTutorial.style.display = "block";
        let textoJogador = "Esse é <strong>você</strong>. Aqui você pode acompanhar seu personagem e suas ações durante o combate.";

        let textoInimigo = "Esse é o seu <strong>inimigo</strong>. Aqui você pode acompanhar o adversário e ações de quem está enfrentando.";

        divTutorial.style.display = "block";

        divTutorial.style.top = "23%"
        divTutorial.style.left = "15.1%"
        destacar(personagemJogador, true);
        await escreverTexto(textTutorial, textoJogador);
        await esperarAnimacao(500);
        destacar(personagemJogador, false);

        divTutorial.style.top = "13%"
        divTutorial.style.left = "38%"
        destacar(personagemInimigo, true);
        await escreverTexto(textTutorial, textoInimigo);
        await esperarAnimacao(500);
        destacar(personagemInimigo, false);

    }

    async function changeTextToAcaoTutorial() {

        let texto = "Essa é a área de <strong>Ações</strong>, onde você escolhe qual ação deseja realizar. Algumas ações especiais, como <strong>Defender</strong> e <strong>Atacar Forte</strong>, possuem um <strong>Tempo de Recarga</strong>, sendo necessário aguardar alguns turnos antes de utilizá-las novamente.";

        let textoAtaque = "Essa ação realiza um <strong>Ataque</strong> comum, causando dano ao inimigo.";

        let textoAtaqueForte = "Essa ação realiza um <strong>Ataque Forte</strong>, causando <strong>1,5x o seu dano</strong>. Porém, possui <strong>2 turnos de recarga</strong>.";

        let textoDefender = "Essa ação permite <strong>Defender</strong>, aumentando sua <strong>chance de esquiva em 1,5x</strong>. Possui <strong>1 turno de recarga</strong>.";

        let textoChorar = "Essa ação permite <strong>Chorar</strong>. Não causa dano nem possui efeitos especiais, mas pode ser útil em <strong>momentos desesperadores</strong>.";

        bloquearBatalha(true);
        divTutorial.style.display = "block";

        divTutorial.style.top = "70%"
        divTutorial.style.left = "15.1%"
        btnAtaques.classList.add("btn-pisca")
        await escreverTexto(textTutorial, texto)
        btnAtaques.classList.remove("btn-pisca")
        await esperarAnimacao(800)

        divTutorial.style.top = "61%"
        divTutorial.style.left = "18.6%"
        ataques.children.item(0).classList.add("btn-pisca")
        await escreverTexto(textTutorial, textoAtaque)
        ataques.children.item(0).classList.remove("btn-pisca")
        await esperarAnimacao(800)

        divTutorial.style.top = "61%"
        divTutorial.style.left = "62%"
        ataques.children.item(1).classList.add("btn-pisca")
        await escreverTexto(textTutorial, textoAtaqueForte)
        ataques.children.item(1).classList.remove("btn-pisca")
        await esperarAnimacao(800)

        divTutorial.style.top = "75%"
        divTutorial.style.left = "18.6%"
        ataques.children.item(2).classList.add("btn-pisca")
        await escreverTexto(textTutorial, textoDefender)
        ataques.children.item(2).classList.remove("btn-pisca")
        await esperarAnimacao(800)

        divTutorial.style.top = "75%"
        divTutorial.style.left = "62%"
        ataques.children.item(3).classList.add("btn-pisca")
        await escreverTexto(textTutorial, textoChorar)
        ataques.children.item(3).classList.remove("btn-pisca")
        await esperarAnimacao(800)
    }

    async function changeTextToItemTutorial() {

        let texto = "Essa é a área de <strong>Itens</strong>, onde você pode utilizar itens durante o combate. Existem itens de uso instantâneo, como <strong>Poções de Cura</strong>, e itens que concedem <strong>efeitos temporários</strong>, como <strong>Poções de Fortalecimento</strong>.";
        let textoInventario = "Essa é o seu <strong>Inventário</strong>. Nele você pode ver todos os itens disponíveis durante o combate. A coluna <strong>Item</strong> mostra o nome do item, <strong>Tipo</strong> indica sua categoria, <strong>Descrição</strong> mostra o efeito que ele possui e, em <strong>Ação</strong>, você pode utilizar o item.";
        let inventario = document.getElementById("itens");

        mostrarItens();
        bloquearBatalha(true);

        divTutorial.style.display = "block";
        divTutorial.style.top = "75%";
        divTutorial.style.left = "15.1%";
        btnItens.classList.add("btn-pisca");

        await escreverTexto(textTutorial, texto);
        await esperarAnimacao(500)
        btnItens.classList.remove("btn-pisca");

        divTutorial.style.top = "52%";
        divTutorial.style.left = "36%";
        inventario.classList.add("btn-pisca")
        await escreverTexto(textTutorial, textoInventario);
        inventario.classList.remove("btn-pisca");
    }

    async function changeTextToFugaTutorial() {
        mostrarAtaques();
        bloquearBatalha(true);
        divTutorial.style.display = "block";
        divTutorial.style.width = "20%";
        divTutorial.style.top = "77%"
        divTutorial.style.left = "0"
        btnFugir.classList.add("btn-pisca")

        let texto = "O botão de <strong>Fugir</strong> permite que você escape do combate.";
        await escreverTexto(textTutorial, texto)
        btnFugir.classList.remove("btn-pisca")
    }

    function escreverTexto(elemento, html, velocidade = 30) {
        return new Promise(resolve => {
            elemento.innerHTML = html;

            const walker = document.createTreeWalker(
                elemento,
                NodeFilter.SHOW_TEXT
            );

            const textos = [];
            let node;

            while (node = walker.nextNode()) {
                textos.push(node);
            }

            const originais = textos.map(node => node.textContent);

            textos.forEach(node => node.textContent = "");

            let indiceTexto = 0;
            let indiceCaractere = 0;

            function escrever() {
                if (indiceTexto >= textos.length) {
                    resolve();
                    return;
                }

                const texto = originais[indiceTexto];

                textos[indiceTexto].textContent += texto[indiceCaractere];
                indiceCaractere++;

                if (indiceCaractere >= texto.length) {
                    indiceTexto++;
                    indiceCaractere = 0;
                }

                setTimeout(escrever, velocidade);
            }

            escrever();
        });
    }

    function escurecerBatalha($escurecer) {
        const batalha = document.getElementsByClassName("batalha");
        batalha.item(0).style.filter = $escurecer ? "brightness(0.5)" : "brightness(1.0)"
    }

    async function initTutorial(){
        escurecerBatalha(true)
        await changeTextToBatalhaTutorial();
        await esperarAnimacao(500);
        await changeTextToAcaoTutorial();
        await esperarAnimacao(800);
        await changeTextToItemTutorial();
        await esperarAnimacao(1000);
        await changeTextToFugaTutorial();
        await esperarAnimacao(1500);
        escurecerBatalha(false)
        divTutorial.style.display = "none";
        bloquearBatalha(false);
    }

    function destacar($elemento, $destacar){
        $elemento.style.filter = $destacar
                    ? 'brightness(2)' : "none"
    }
</script>

</body>

</html>