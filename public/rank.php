<?php
require_once "../Repository/EstatisticaRepository.php";

$rank = EstatisticaRepository::getRank();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ranking</title>
    <link rel="stylesheet" href="resources/css/rank.css">
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
</head>
<body>
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
        echo
                "<tr>
            <td>" . $posicao->POSICAO . "º</td>
            <td><em>" . $posicao->NOME . "</em></td>
            <td>" . EstatisticaRepository::formatTime($posicao->TEMPO_CONCLUSAO) . "</td>
            </tr>";
    }
    ?>
    </tbody>
</table>
<button id="menu-button" onclick="window.location.href = '../public/index.php'">Menu</button>
</body>
</html>