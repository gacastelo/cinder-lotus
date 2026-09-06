<?php
require "../Service/CenarioService.php";
require_once "../Service/AnimationService.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["fases"])){
    CenarioService::generateAllFases();
}

if (isset($_GET["cena"])) {
    if (CenarioService::isUnlocked($_GET["cena"])){
        $_SESSION["cenarioAtualId"] = $_GET["cena"];
        CenarioService::getCena($_GET["cena"]);
    }
    else{
        header("Location: mapa.php");
        exit();
    }
}

if (isset($_SESSION["inimigo"])){
    unset($_SESSION["inimigo"]);
}

if (!isset($_SESSION["cenarioAtualId"])) {
    $_SESSION["cenarioAtualId"] = 1;
}

if (isset($_SESSION["acoes"])){
    unset($_SESSION["acoes"]);
}

//for ($i = 2; $i < 13 ; $i++) {
//    $_SESSION["cenarioService"]->unlockLevel($i);
//}
//var_dump($_SESSION["cenarios"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mapa do Jogo</title>

    <link rel="stylesheet" href="resources/css/mapa.css">
</head>

<body>

<div id="mapa">

    <div class="agua"></div>

    <div id="informacoes">
        <strong>Mapa do Mundo</strong><br>
        Use as setas do teclado para se mover <br>
        aperte ENTER para entrar na fase
    </div>

    <img src="img/chibi_hero.gif" alt="Hero_mini" id="jogador">
    <div id="mensagem">
        Você está na Fase 1
    </div>

</div>


<script>
    // CÓDIGO JAVASCRIPT FEITO COM AUXÍLIO DE IA, PARA DEIXAR MAIS BONITA A NAVEGAÇÃO ENTRE FASES POR MEIO DE MAPA INTERATIVO

    const fases = <?= json_encode($_SESSION["fases"]) ?>;

    /*
    ================================
    POSIÇÃO ATUAL DO JOGADOR
    ================================
    */

    let faseAtual = <?= $_SESSION["cenarioAtualId"]?>;


    /*
    ================================
    ELEMENTOS HTML
    ================================
    */

    const mapa = document.getElementById("mapa");

    const jogador = document.getElementById("jogador");

    const mensagem = document.getElementById("mensagem");


    /*
    ================================
    CRIAR FASES
    ================================
    */

    function criarFases() {

        Object.values(fases).forEach(fase => {

            const elemento = document.createElement("div");

            elemento.classList.add("fase");

            if (fase.bloqueada) {
                elemento.classList.add("bloqueada");
                elemento.innerHTML = "🔒";
            } else {
                elemento.innerHTML = fase.id;
            }


            elemento.style.left = fase.x + "%";
            elemento.style.top = fase.y + "%";


            /*
            Clicar diretamente na fase
            */

            elemento.addEventListener("click", () => {

                if (fase.bloqueada) {

                    mostrarMensagem(
                        "Esta fase está bloqueada!"
                    );

                    return;
                }


                /*
                Só permite ir para uma fase
                diretamente conectada
                */

                const conexoes = Object.values(
                    fases[faseAtual].conexoes
                );


                if (conexoes.includes(fase.id)) {

                    moverPara(fase.id);

                }

            });


            mapa.appendChild(elemento);

        });

    }


    /*
    ================================
    MOVER JOGADOR
    ================================
    */

    function moverPara(idFase) {

        const novaFase = fases[idFase];


        /*
        Verificar se está bloqueada
        */

        if (novaFase.bloqueada) {

            mostrarMensagem(
                novaFase.nome + " está bloqueada!"
            );

            return;

        }


        /*
        Atualizar posição
        */

        jogador.style.left = novaFase.x + "%";

        jogador.style.top = novaFase.y + "%";


        /*
        Atualizar fase atual
        */

        faseAtual = idFase;


        mostrarMensagem(
            "Você está em: " + novaFase.nome
        );

    }


    /*
    ================================
    MENSAGEM
    ================================
    */

    function mostrarMensagem(texto) {

        mensagem.textContent = texto;

    }


    /*
    ================================
    MOVIMENTAÇÃO PELO TECLADO
    ================================
    */

    document.addEventListener("keydown", event => {

        const tecla = event.key;


        /*
        Impedir a página de rolar
        */

        if (
            tecla === "ArrowUp" ||
            tecla === "ArrowDown" ||
            tecla === "ArrowLeft" ||
            tecla === "ArrowRight"
        ) {

            event.preventDefault();

        }


        let direcao = null;


        /*
        Converter tecla em direção
        */

        if (tecla === "ArrowUp") {
            direcao = "cima";
        } else if (tecla === "ArrowDown") {
            direcao = "baixo";
        } else if (tecla === "ArrowLeft") {
            direcao = "esquerda";
        } else if (tecla === "ArrowRight") {
            direcao = "direita";
        }


        /*
        Se encontrou uma direção válida
        */

        if (direcao) {

            const conexoes =
                fases[faseAtual].conexoes;


            /*
            Verificar se existe um caminho
            naquela direção
            */

            if (conexoes[direcao]) {

                moverPara(
                    conexoes[direcao]
                );

            } else {

                mostrarMensagem(
                    "Não existe caminho nessa direção!"
                );

            }

        }
        //Feito por mim, eu mesmo juro - Gabriel Castelo
        if (tecla === "Enter") {
            const url = new URL(window.location.href);
            url.searchParams.set("cena", faseAtual);
            window.location.href = url.toString();
        }

    });


    /*
    ================================
    CRIAR OS CAMINHOS
    ================================
    */

    function criarCaminhos() {
        const caminhosCriados = [];

        // Proporção do mapa (Altura / Largura) = 9 / 16
        const proporcaoTela = 9 / 16;

        Object.values(fases).forEach(fase => {
            Object.values(fase.conexoes).forEach(destinoId => {
                const destino = fases[destinoId];

                // Evitar criar o mesmo caminho duas vezes
                const identificador = [fase.id, destinoId].sort().join("-");
                if (caminhosCriados.includes(identificador)) {
                    return;
                }
                caminhosCriados.push(identificador);

                /*
                1. Calcular diferenças.
                Ajustamos o Y multiplicando pela proporção para
                que 1% de Y tenha o mesmo "peso" que 1% de X na matemática.
                */
                const diferencaX = destino.x - fase.x;

                // Y ajustado para a escala da largura
                const diferencaY = destino.y - fase.y;
                const diferencaY_ajustada = diferencaY * proporcaoTela;

                // Distância ajustada (baseada na largura total)
                const distancia = Math.sqrt(
                    diferencaX * diferencaX +
                    diferencaY_ajustada * diferencaY_ajustada
                );

                // Ângulo ajustado com o Y proporcional
                const angulo = Math.atan2(
                    diferencaY_ajustada,
                    diferencaX
                ) * (180 / Math.PI);

                /*
                2. Criar e posicionar o elemento
                */
                const caminho = document.createElement("div");
                caminho.classList.add("caminho");

                // Posiciona o início da linha exatamente na fase de origem
                caminho.style.left = fase.x + "%";
                caminho.style.top = fase.y + "%";

                // O tamanho agora reflete a proporção real
                caminho.style.width = distancia + "%";

                // SUPER IMPORTANTE: Força a rotação a acontecer a partir da ponta esquerda!
                caminho.style.transformOrigin = "0 50%"; // "left center"

                caminho.style.transform = `translateY(-50%) rotate(${angulo}deg)`;

                mapa.appendChild(caminho);
            });
        });
    }


    /*
    ================================
    INICIAR MAPA
    ================================
    */

    criarCaminhos();

    criarFases();

    moverPara(faseAtual);

</script>

</body>
</html>