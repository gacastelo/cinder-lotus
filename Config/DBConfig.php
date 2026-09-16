<?php

class DBConfig
{
    public static string $servername = "localhost";
    public static string $username = "root";
    public static string $password = "";
    public static string $database = "cinder_lotus";


    public static function getConn(): PDO
    {
        try {
            $conn = new \PDO("mysql:host=".self::$servername.";dbname=".self::$database, self::$username, self::$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Could not connect. " . $e->getMessage());
        }
        return $conn;
    }

    private static function initializeEstatisticas(): void
    {
        $conn = self::getConn();
        $sql = "CREATE TABLE IF NOT EXISTS ".self::$database.".ESTATISTICAS (
                ID BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                NOME VARCHAR(255) NOT NULL,
            
                ATAQUES_TOTAIS INT UNSIGNED NOT NULL DEFAULT 0,
                ATAQUES_ACERTADOS INT UNSIGNED NOT NULL DEFAULT 0,
                ITENS_CONSUMIDOS INT UNSIGNED NOT NULL DEFAULT 0,
            
                COMBATES_GANHOS INT UNSIGNED NOT NULL DEFAULT 0,
                COMBATES_FUGAS INT UNSIGNED NOT NULL DEFAULT 0,
            
                DESAFIOS_SUPERADOS INT UNSIGNED NOT NULL DEFAULT 0,
                DESAFIOS_FUGAS INT UNSIGNED NOT NULL DEFAULT 0,
                DESAFIOS_FALHOS INT UNSIGNED NOT NULL DEFAULT 0,
            
                DANO_CAUSADO INT UNSIGNED NOT NULL DEFAULT 0,
                DANO_SOFRIDO INT UNSIGNED NOT NULL DEFAULT 0,
            
                TEMPO_CONCLUSAO BIGINT UNSIGNED NULL,
            
                CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UPDATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP);";

        try {
            $conn->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    public static function initialize(): void
    {
        self::initializeEstatisticas();
    }

}