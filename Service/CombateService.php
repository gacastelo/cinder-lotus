<?php
require "../Service/AnimationService.php";
require "../Service/CenarioService.php";
require "../Service/LootService.php";
require_once "../Service/EstatisticaService.php";
require_once "../Service/BestiarioService.php";
class CombateService
{
    private function getTabelaLv1(): array
    {
        return [
            new Inimigo("Focanana", 30, 3, 10, 4, LootService::getLoots(1, rand(0, 2)), "focanana_base"),
            new Inimigo("Goblin", 35, 2, 5, 5, LootService::getLoots(1, rand(0, 2)), "goblin_base"),
            new Inimigo("Macharanguejo", 40, 2, 8, 5, LootService::getLoots(1, rand(0, 2)), "macharanguejo_base"),
            new Inimigo("Mofopão", 60, -2, 5, 3, LootService::getLoots(1, rand(0, 2)), "mofopão_base"),
            new Inimigo("Orcopete", 55, -2, 6, 2, LootService::getLoots(1, rand(0, 2)), "orcopete_base"),
            new Inimigo("Punkalo", 30, 6, 6, 10, LootService::getLoots(1, rand(0, 2)), "punkalo_base"),
        ];
    }

    private function getTabelaLv2(): array
    {
        return [
            new Inimigo("Focanana de Elite", 55, 5, 17, 6, LootService::getLoots(2, rand(1, 2)), "focanana_de_elite"),
            new Inimigo("Goblin de Elite", 65, 3, 9, 8, LootService::getLoots(2, rand(1, 2)), "goblin_de_elite"),
            new Inimigo("Goblin Elitíssimo", 60, 8, 11, 12, LootService::getLoots(2, rand(1, 3)), "goblin_elitíssimo"),
            new Inimigo("Macharanguejo de Elite", 90, 4, 15, 8, LootService::getLoots(2, rand(1, 2)), "macharanguejo_de_elite"),
            new Inimigo("Mofopão de Elite", 110, -3, 9, 6, LootService::getLoots(2, rand(1, 2)), "mofopão_de_elite"),
            new Inimigo("Orcopete de Elite", 90, -3, 11, 4, LootService::getLoots(2, rand(1, 2)), "orcopete_de_elite"),
            new Inimigo("Pirâmuitos", 140, 7, 13, 15, LootService::getLoots(2, rand(0, 2)), "pirâmuitos_base"),
            new Inimigo("Punkalo de Elite", 50, 10, 10, 16, LootService::getLoots(2, rand(1, 2)), "punkalo_de_elite"),
        ];
    }

    private function getTabelaLv3(): array
    {
        return [
            new Inimigo("Focanana Elitíssimo", 100, 7, 25, 8, LootService::getLoots(3, rand(1, 3)), "focanana_elitíssimo"),
            new Inimigo("Macharanguejo Elitíssimo", 160, 5, 22, 12, LootService::getLoots(3, rand(1, 3)), "macharanguejo_elitíssimo"),
            new Inimigo("Mofopão Elitíssimo", 240, -4, 14, 10, LootService::getLoots(3, rand(1, 3)), "mofopão_elitíssimo"),
            new Inimigo("Orcopete Elitíssimo", 220, -4, 17, 6, LootService::getLoots(3, rand(1, 3)), "orcopete_elitíssimo"),
            new Inimigo("Pirâmuitos de Elite", 220, 9, 20, 20, LootService::getLoots(3, rand(1, 2)), "pirâmuitos_de_elite"),
            new Inimigo("Pirâmuitos Elitíssimo", 280, 12, 24, 25, LootService::getLoots(3, rand(1, 3)), "pirâmuitos_elitíssimo"),
            new Inimigo("Punkalo Elitíssimo", 85, 14, 17, 22, LootService::getLoots(3, rand(1, 3)), "punkalo_elitíssimo"),
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
            new Inimigo("Boneco de Treino", 15, -5, 0, 1, [], "boneco_de_treino")
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
        $inimigo = $table[array_rand($table)];
        BestiarioService::increaseEncontrados($inimigo->getBestiarioId());
        return $inimigo;
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
            BestiarioService::increaseDerrotados($_SESSION["inimigo"]->getBestiarioId());
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

        if ($inimigoPrimeiro && !$_SESSION["inimigo"]->is_dead()) {
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

        if (!$inimigoPrimeiro && !$_SESSION["inimigo"]->is_dead()) {
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