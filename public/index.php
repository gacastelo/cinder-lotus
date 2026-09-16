<?php
require_once "../Model/Entidades/Player.php";
require_once "../Service/EstatisticaService.php";
require_once "../Config/DBConfig.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
</head>
<body>
    <main>
        <h1>Tela Inicio</h1>
        <form method="post">
            <label>
                <input type="text" maxlength="50" placeholder="Nome do Herói" name="nome_heroi">
            </label>
            <input type="submit" value="Iniciar Jogo">
        </form>
    </main>
</body>
</html>