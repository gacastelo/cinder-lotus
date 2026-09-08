<?php
require "../AbsModel/AbsEntity.php";
require "../Model/Itens/Consumivel.php";
require "../Model/Itens/Equipamento.php";

class Player extends AbsEntity
{
    private array $equipamento = ["cabeca" => null, "peitoral" => null, "botas" => null, "mao_principal" => null, "mao_secundaria" => null];
    private array $inventario = [];

    private array $buffs = ["dano" => ["value" => 0, "duration" => 0], "velocidade" => ["value" => 0, "duration" => 0], "chance_esquiva" => ["value" => 0, "duration" => 0], "vida_max" => ["value" => 0, "duration" => 0]];

    public function __construct(string $nome = "Héroi", string $link_imagem = "./img/default_hero.gif")
    {
        $this->nome = $nome;
        $this->vida_maxima = 100;
        $this->vida_atual = $this->vida_maxima;
        $this->velocidade = 0;
        $this->dano = 5;
        $this->chance_esquiva = 0;
        $this->link_imagem = $link_imagem;
        $this->guardarItem(new Consumivel("Poçao de Cura Mínima", "Cura 10 de vida", ["metodo" => "heal", "args" => [10]]));
        $this->guardarItem(new Consumivel("Poçao de Fortalecimento", "Aumenta Vida Max", ["metodo" => "increaseVidaMax", "args" => [15, 5]]));
        $this->equip(new Equipamento("Espada Fodedora", "mao_principal", "espada Sigma", 0, 0, 45, 0, false));


    }

    public function guardarItem($item): void
    {
        $this->inventario[$item->getId()] = $item;
    }

    public function useItem($itemId): void
    {
        $item = $this->inventario[$itemId];
        if ($item instanceof \Equipamento) {
            $this->equip($item);
        }
        if ($item instanceof \Consumivel) {
            $this->consume($item->getId());
        }
    }

    public function equip(Equipamento $equip): void
    {
        $tipo = $equip->getAtribute("tipo");
        $equip->toggleEquipped();
        if (!isset($this->equipamento[$tipo])) {
            $this->equipamento[$tipo] = $equip;
        } else {
            $replace = $this->equipamento[$tipo];
            $this->equipamento[$tipo] = $equip;
            $replace->toggleEquipped();
            $this->guardarItem($replace);
        }
    }

    public function getEquipamento(): array
    {
        return $this->equipamento;
    }

    public function getInventario(): array
    {
        return $this->inventario;
    }

    public function heal(int $amount): void
    {
        if ($this->vida_atual + $amount > $this->getVida_Maxima()) {
            $this->vida_atual = $this->getVida_Maxima();
        } else {
            $this->vida_atual += $amount;
        }
    }

    public function increaseVidaMax(int $amount, int $duration): void
    {
        $this->buff("vida_max", $amount, $duration);
        $this->heal($amount);
    }

    public function buff(string $type, int $value, int $duration): void
    {
        $this->buffs[$type] = ["value" => $value, "duration" => $duration];
    }

    public function getBuffs(): array
    {
        return $this->buffs;
    }

    public function decreaseBuffDuration(): void
    {
        foreach ($this->buffs as &$buff) {
            if ($buff["duration"] > 0) {
                $buff["duration"]--;
            }

            if ($buff["duration"] === 0) {
                $buff["value"] = 0;
            }
        }
        unset($buff);
    }

    public function decreaseAttacksCooldown(): void
    {
        //TODO: if make different attacks make they cooldown here
    }

    public function consume(string $id): void
    {
        $consumivel = $this->inventario[$id];
        $efeito = $consumivel->getEfeito();
        $metodo = $efeito['metodo'];
        $args = $efeito['args'];
        $this->$metodo(...$args);
        unset($this->inventario[$id]);
    }

    private function getEquipamentoBuffs(string $type): int
    {
        $buffs = 0;
        foreach ($this->equipamento as $equip) {
            if (isset($equip)){
                $stats = $equip->getStats();
                $buffs += $stats[$type];
            }
        }
        return $buffs;
    }

    public function getVida_Maxima(): int
    {
        return $this->vida_maxima + $this->buffs["vida_max"]["value"] + $this->getEquipamentoBuffs("vida_max");
    }

    public function getDano(): int
    {
        return $this->dano + $this->buffs["dano"]["value"] + $this->getEquipamentoBuffs("dano");
    }
    public function getVelocidade(): int
    {
        return $this->velocidade + $this->buffs["velocidade"]["value"] + $this->getEquipamentoBuffs("velocidade");
    }

    public function getChanceEquiva(): int
    {
        return $this->chance_esquiva + $this->buffs["$this->chance_esquiva"]["value"] + $this->getEquipamentoBuffs("$this->chance_esquiva");
    }
    public function attack(AbsEntity $entity) : void {
        AnimationService::acaoAtacar("jogador");
        parent::attack($entity);

    }
    public function take_damage(int $damage): void
    {
        AnimationService::acaoDano("jogador", $damage);
        parent::take_damage($damage);
    }
}