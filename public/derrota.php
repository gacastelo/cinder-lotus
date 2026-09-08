<?php
require_once "../Model/Entidades/Player.php";
session_start();


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
    </main>
    <script>
        function goToMenu(){
            window.location.href = "../public/index.php"
        }
    </script>
</body>
</html>
