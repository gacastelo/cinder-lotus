<?php
require_once "../Service/ConsumivelService.php";
require_once "../Service/EquipamentoService.php";
class LootService
{
    public static function getLoots(string $level, int $numberOfItems = 1): array
    {
        $loot = [];
        for ($i = 0; $i < $numberOfItems; $i++) {
            $loot[] = self::getLoot($level);
        }

        if (rand(1,5) == 1){
            $ConsumivelService = new ConsumivelService();
            $loot[] = $ConsumivelService->gerarPocaoCura($level);
        }

        return $loot;
    }

    private static function getLoot(string $level): Equipamento|Consumivel
    {
            if (rand(0,1)) {
                $ConsumivelService = new ConsumivelService();
                return $ConsumivelService->gerarConsumivel($level);
            } else {
                $EquipamentoService = new EquipamentoService();
                return $EquipamentoService->gerarEquipamento($level);
            }
    }
}