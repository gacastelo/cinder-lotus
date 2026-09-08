<?php
require_once "../Service/ConsumivelService.php";

class LootService
{
    public static function getLoot(string $level, int $numberOfItems = 1): array
    {
        $loot = [];
        for ($i = 1; $i <= $numberOfItems; $i++) {
            if (rand(0, 2) < 2) {
                $ConsumivelService = new ConsumivelService();
                $loot[] = $ConsumivelService->gerarConsumivel($level);
            } else {
                $loot[] = null;
            }
        }
        return $loot;
    }
}