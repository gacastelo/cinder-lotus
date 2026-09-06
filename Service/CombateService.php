<?php
require "../Service/AnimationService.php";
require "../Service/CenarioService.php";
class CombateService
{
    private function getTabelaLv1(): array
    {
        return [
            new Inimigo("Goblin", 50, 2, 5, 5, [], ""),
            new Inimigo("Goblin", 50, 2, 5, 5, [], "")
        ];
    }
    private function getTabelaLv2(): array
    {
        return [
            new Inimigo("Goblin", 50, 2, 5, 5, [], ""),
            new Inimigo("Goblin", 50, 2, 5, 5, [], "")
        ];
    }
    private function getTabelaLv3(): array
    {
        return [
            new Inimigo("Goblin", 50, 2, 5, 5, [], ""),
            new Inimigo("Goblin", 50, 2, 5, 5, [], "")
        ];
    }
    private function getTabelaLv4(): array
    {
        return [
            new Inimigo("Goblin Chefe", 500, 20, 50, 25, [], "")
        ];
    }
    private function getTabelaLv0(): array
    {
        return [
            new Inimigo("Goblin de Pano", 25, -5, 1, 1, [], "")
        ];
    }

    public static function getBackground(): string
    {

        $backgrounds = [
            "fundinho.png",
            "sigmainha.png"
        ];

        return $backgrounds[array_rand($backgrounds)];
    }

    public function gerarInimigo(string $level = "1"): \Inimigo
    {
        $get = "getTabelaLv". $level;
        $table = $this->$get();
        return $table[array_rand($table)];
    }

    public function initCombate(string $level): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

        if (!isset($_SESSION["inimigo"])){
            $inimigo = $this->gerarInimigo($level);
            $_SESSION["inimigo"] = $inimigo;
        }
    }

    private function check_resultado(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        if ($_SESSION["inimigo"]->is_dead()) {
            $this->destribuirLoot();
            AnimationService::vitoria();
            CenarioService::unlockNextLevel($_SESSION["cenarioAtualId"]);
        }
        if ($_SESSION["player"]->is_dead()) {
            AnimationService::derrota();
        }
    }

    private function destribuirLoot(): void
    {
        $loot = $_SESSION["inimigo"]->get_loot();
        foreach ($loot as $item) {
            $_SESSION["player"]->guardarItem($item);
        }
    }

    private function resultado(): void
    {
        AnimationService::limpar();
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        if ($_SESSION["inimigo"]->getVelocidade() > $_SESSION["player"]->getVelocidade()) {
            $_SESSION["inimigo"]->attack($_SESSION["player"]);
            $this->check_resultado();
            if (isset($_GET["ataque"])){
                $_SESSION["player"]->attack($_SESSION["inimigo"]);
                $this->check_resultado();
            }
        } else {
            if (isset($_GET["ataque"])){
                $_SESSION["player"]->attack($_SESSION["inimigo"]);
                $this->check_resultado();
            }
            $_SESSION["inimigo"]->attack($_SESSION["player"]);
            $this->check_resultado();
        }
    }
    public function passarTurno(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $this->resultado();
        $_SESSION["player"]->decreaseBuffDuration();
        $_SESSION["player"]->decreaseAttacksCooldown();
        header("Location: combate.php");
        exit();
    }

    public function fugir(): void
    {
        AnimationService::limpar();
        unset($_SESSION["inimigo"]);
        header("location: ../public/mapa.php");
        exit();
    }
}