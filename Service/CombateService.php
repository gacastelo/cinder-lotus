<?php
require "../Service/AnimationService.php";
require "../Service/CenarioService.php";
require "../Service/LootService.php";
require_once "../Service/EstatisticaService.php";
class CombateService
{
    private function getTabelaLv1(): array
    {
        return [
            new Inimigo("Goblin", 35, 2, 5, 5, LootService::getLoots(1, rand(0,2)), "goblin_base"),
            new Inimigo("Macharanguejo",30 , 0, 10, 2, LootService::getLoots(1, rand(0,2)), "macharanguejo_base")
//            new Inimigo("Goblin", 50, 2, 5, 5, [], "img/default_hero.gif")
        ];
    }
    private function getTabelaLv2(): array
    {
        return [
            new Inimigo("Goblin de Elite", 50, 3, 7, 10, LootService::getLoots(2, rand(0,2)), "goblin_azul"),
            new Inimigo("Goblin Elitissímo", 50, 8, 10, 12, LootService::getLoots(2, rand(0,2)), "goblin_rosa"),
            new Inimigo("Macharanguejo de Elite",80 , 2, 15, 3, LootService::getLoots(2, rand(0,2)), "macharanguejo_rosa")
        ];
    }
    private function getTabelaLv3(): array
    {
        return [
            new Inimigo("Macharanguejo Elitissímo",350 , 5, 18, 10, LootService::getLoots(3, rand(0,2)), "macharanguejo_verde")

        ];
    }
    private function getTabelaLv4(): array
    {
        return [
            new Inimigo("Cinder Lotus", 500, 20, 50, 25, [], "cinder_lotus")
        ];
    }
    private function getTabelaLv0(): array
    {
        return [
            new Inimigo("Goblin de Pano", 15, -5, 0, 1, [], "boneco_treino")
        ];
    }

    public static function getBackground(): string
    {

        $backgrounds = [
            "img/cenarios/cena_campo_dia.png",
            "img/cenarios/cena_campo_noite.png",
            "img/cenarios/cena_campo_tarde.png",
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
            EstatisticaService::increaseCombatesGanhos();
            CenarioService::unlockNextLevel($_SESSION["cenarioAtualId"]);
        }
        if ($_SESSION["player"]->is_dead()) {
            AnimationService::derrota();
        }
    }

    private function destribuirLoot(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $loot = $_SESSION["inimigo"]->get_loot();
        $_SESSION["new_loots"] = 0;
        foreach ($loot as $item) {
            $_SESSION["player"]->guardarItem($item);
            $_SESSION["new_loots"]++;
        }
        if ($_SESSION["new_loots"] == 0){
            unset($_SESSION["new_loots"]);
        }
    }

    private function inimigoAtaca(): void
    {
        $_SESSION["inimigo"]->attack($_SESSION["player"]);
        $this->check_resultado();
    }

    private function playerAtaca(): void
    {
        $_SESSION["player"]->attack($_SESSION["inimigo"]);
        $this->check_resultado();
    }

    private function playerAtacaForte(): void
    {
        $_SESSION["player"]->strongAttack($_SESSION["inimigo"]);
        $this->check_resultado();
    }

    private function playerDefende(): void
    {
        $_SESSION["player"]->defender();
        $this->check_resultado();
    }

    private function playerChora(): void
    {
        $_SESSION["player"]->cry();
        $this->check_resultado();
    }

    private function resultado(): void
    {
        AnimationService::limpar();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $inimigoPrimeiro = $_SESSION["inimigo"]->getVelocidade() > $_SESSION["player"]->getVelocidade();

        if (isset($_GET["ataque"])){
            if ($_GET["ataque"] === "Chorar"){
                $this->playerChora();
            }
            if ($_GET["ataque"] === "Defender"){
                $this->playerDefende();
            }
        }

        if ($inimigoPrimeiro) {
            $this->inimigoAtaca();
        }

        if (isset($_GET["ataque"])) {

            if ($_GET["ataque"] === "Ataque"){
                $this->playerAtaca();
            }

            if ($_GET["ataque"] === "AtaqueForte"){
                $this->playerAtacaForte();
            }
        }

        if (!$inimigoPrimeiro) {
            $this->inimigoAtaca();
        }
    }
    public function passarTurno(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $this->resultado();
        $_SESSION["player"]->decreaseBuffDuration();
        $_SESSION["player"]->decreaseDebuffDuration();
        $_SESSION["player"]->decreaseAttacksCooldown();
        header("Location: combate.php");
        exit();
    }

    public function fugir(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        AnimationService::limpar();
        unset($_SESSION["inimigo"]);
        EstatisticaService::increaseCombatesFugas();
        header("location: ../public/mapa.php");
        exit();
    }
}