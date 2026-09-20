<?php
require_once "../Model/Entidades/Player.php";
require_once "../Service/EstatisticaService.php";
require_once "../Config/DBConfig.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["nome_heroi"])){
        $_POST["nome_heroi"] = "Herói";
    }

    if (strlen($_POST["nome_heroi"]) > 255) {
        $_POST["nome_heroi"] = substr($_POST["nome_heroi"], 0, 255);
    }

    $_SESSION = array();
    $_SESSION["player"] = new Player($_POST["nome_heroi"]);
    $_SESSION["tempoInicial"] = (int) (microtime(true) * 1000);
    EstatisticaService::initEstatisticas($_POST["nome_heroi"]);
    header("Location: mapa.php");
    exit();
}
if (!isset($_SERVER["DBStatus"])){
    try {
        DBConfig::initialize();
        $_SERVER["DBStatus"] = "initialized";
    } catch (exception $e){
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tela Inicial</title>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&family=VT323&display=swap" rel="stylesheet">
    <link href="resources/css/index.css" rel="stylesheet">
</head>
<body>
    <main>
        <h1>Cinder Lotus</h1>
        <h2>Adventure</h2>
        <form method="post">
            <label>
                <input type="text" maxlength="50" placeholder="Nome do Herói" name="nome_heroi" required>
            </label>

            <div>
                <input type="submit" value="Iniciar Jogo">
                <button onclick="window.location.href = './rank.php'">Ver Ranking</button>
            </div>
            <button onclick="window.location.href = './creditos.html'">Créditos</button>
        </form>

    </main>
</body>
</html>