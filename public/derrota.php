<?php
require_once "../Model/Entidades/Player.php";
require_once "../Repository/EstatisticaRepository.php";
require_once "../Service/EstatisticaService.php";
session_start();

if (!$_SESSION["estatisticas"]->isSaved()){
    EstatisticaService::setTempoConclusao();
    EstatisticaRepository::insertEstatisticaAtual();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Derrota</title>
</head>
<body>
    <!--TODO: Estaticastica -->
    <main>
        <h1>Derrota</h1>
        <button id="menu-button" onclick="goToMenu()">Menu</button>
        <?php

        var_dump($_SESSION["estatisticas"]);
        ?>
    </main>
    <script>
        function goToMenu(){
            window.location.href = "../public/index.php"
        }
    </script>
</body>
</html>
