<?php
require_once "../Model/Entidades/Player.php";
require_once "../Service/EstatisticaService.php";
require_once "../Repository/EstatisticaRepository.php";
session_start();

if (!$_SESSION["matouBossFinal"]) {
    header("location: ../public/mapa.php");
    exit();
}

if (!$_SESSION["estatisticas"]->isSaved()) {
    EstatisticaService::setTempoConclusao();
    EstatisticaRepository::insertEstatisticaAtual();
}
$rank = EstatisticaRepository::getRank();
$rankeado = false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vitória</title>
    <link rel="stylesheet" href="resources/css/vitoria.css">
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
</head>
<body>
<h1>Parabéns <?= $_SESSION["player"]->getNome() ?>, você venceu!</h1>
<table id="rankingTable">
    <thead>
    <tr>
        <th>Posição</th>
        <th>Nome</th>
        <th>Tempo de Conclusão (s)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($rank as $posicao) {
        if ($posicao->ID == $_SESSION["ultimoSalvo"]) {
            $rankeado = true;
            echo
                "<tr>
                <td>*" . $posicao->POSICAO . "º*</td>
                <td><em>*" . $posicao->NOME . "*</em></td>
                <td>*" . EstatisticaRepository::formatTime($posicao->TEMPO_CONCLUSAO) . "*</td>
                </tr>";
            continue;
        }
        echo
            "<tr>
            <td>" . $posicao->POSICAO . "º</td>
            <td><em>" . $posicao->NOME . "</em></td>
            <td>" . EstatisticaRepository::formatTime($posicao->TEMPO_CONCLUSAO) . "</td>
            </tr>";

    }
    if (!$rankeado) {
        $query = EstatisticaRepository::getPosicaoRank($_SESSION["ultimoSalvo"]);
        echo
        "<tr id='reduce'>
            <td>...</td>
            <td><em>...</em></td>
            <td>...</td>
            </tr>";

        echo
                "<tr>
            <td>*" . $query->POSICAO . "º*</td>
            <td><em>*" . $query->NOME . "*</em></td>
            <td>*" . EstatisticaRepository::formatTime($query->TEMPO_CONCLUSAO) . "*</td>
            </tr>";
    }
    ?>
    </tbody>
</table>
<button id="menu-button" onclick="window.location.href = '../public/index.php'">Menu</button>
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
</body>
</html>
