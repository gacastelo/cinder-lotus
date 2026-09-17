<?php
require_once "../Model/Entidades/Player.php";
require_once "../Repository/EstatisticaRepository.php";
require_once "../Service/EstatisticaService.php";
session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Derrota</title>
    <style>

    </style>
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Derrota</h1>
        <?php $_SESSION["estatisticas"]->getHTMLTable(); ?>
        <button id="menu-button" onclick="window.location.href = '../public/index.php'">Menu</button>
    </main>
</body>
</html>
