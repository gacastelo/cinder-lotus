<?php
require_once "../Model/Entidades/Player.php";
require_once "../Repository/EstatisticaRepository.php";
require_once "../Service/EstatisticaService.php";
session_start();
if (!$_SESSION["estatisticas"]->isSaved()){
    EstatisticaService::setTempoConclusao();
    $_SESSION["estatisticas"]->save();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Derrota</title>
    <link href="resources/css/derrota.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pixelify%20Sans' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Derrota</h1>
        <?php $_SESSION["estatisticas"]->getHTMLTable(); ?>
        <button id="menu-button" onclick="window.location.href = '../public/index.php'">Menu</button>
    </main>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        const som = new Audio('./resources/audio/LOZ_Recorder_edit.mp3');
        som.loop = true;
        som.play();
    </script>
</body>
</html>
