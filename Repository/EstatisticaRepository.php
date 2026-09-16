<?php
require_once "../Config/DBConfig.php";
class EstatisticaRepository
{

    public static function insertEstatisticaAtual(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $conn = DBConfig::getConn();
        $sql = "INSERT INTO `" . DBConfig::$database . "`.`ESTATISTICAS` (NOME, ATAQUES_TOTAIS, ATAQUES_ACERTADOS, ITENS_CONSUMIDOS, COMBATES_GANHOS, COMBATES_FUGAS, DESAFIOS_SUPERADOS, DESAFIOS_FUGAS, DESAFIOS_FALHOS, DANO_CAUSADO, DANO_SOFRIDO, TEMPO_CONCLUSAO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $stmt->execute(EstatisticaService::getAtualEstatisticaValues());
            $_SESSION["estatisticas"]->save();
        } catch (PDOException $e) {
            $conn->rollBack();
            echo $e->getMessage();
        }

        $conn = null;
    }

    public static function getEstatisticas(): array
    {
        $conn = DBConfig::getConn();
        $sql = "SELECT * FROM `estatisticas`;";

        try {
            $result = $conn->query($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;

        return $result->fetchAll(PDO::FETCH_OBJ);
    }

}