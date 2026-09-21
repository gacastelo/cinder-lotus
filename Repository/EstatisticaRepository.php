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
        $valores = EstatisticaService::getAtualEstatisticaValues();
        $stmt->execute($valores);

        $_SESSION["estatisticas"]->save();
        $_SESSION["ultimoSalvo"] = $conn->lastInsertId();
        
        $conn->commit();
        $conn = null;

    } catch (PDOException $e) {

        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("Erro ao inserir estatística: " . $e->getMessage());
    }
    }

    public static function getEstatisticas(int $id): array
    {
        $conn = DBConfig::getConn();
        $sql = "SELECT * FROM `estatisticas` WHERE ID = :id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(["id" => $id]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;

        return $result->fetch(PDO::FETCH_OBJ);
    }

    public static function getRank(): array
    {
        $conn = DBConfig::getConn();
        $sql = "SELECT ROW_NUMBER() OVER (ORDER BY TEMPO_CONCLUSAO) AS POSICAO,
                ID, NOME, TEMPO_CONCLUSAO 
                FROM `estatisticas`
                LIMIT 10";

        try {
            $result = $conn->query($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;

        return $result->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getPosicaoRank(int $id): stdClass
    {
        $conn = DBConfig::getConn();

        $sql = "
        SELECT ID, POSICAO, NOME, TEMPO_CONCLUSAO
        FROM (
            SELECT 
                ID,
            	NOME,
            	TEMPO_CONCLUSAO,
                ROW_NUMBER() OVER (ORDER BY TEMPO_CONCLUSAO ASC) AS POSICAO
            FROM estatisticas
        ) AS ranking
        WHERE ID = :id;
    ";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(["id" => $id]);

            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
        return $resultado;
    }

    public static function formatTime($tempo): string
    {
        $minutos = floor($tempo / 60000);
        $segundos = floor(($tempo % 60000) / 1000);
        $milisegundos = $tempo % 1000;
        return sprintf('%02d:%02d.%03d', $minutos, $segundos, $milisegundos);
    }
}