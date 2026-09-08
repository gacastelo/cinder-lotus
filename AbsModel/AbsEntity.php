<?php
class AbsEntity
{
    protected string $nome;
    protected int $vida_maxima;
    protected int $vida_atual;
    protected bool $is_dead = false;
    protected int $velocidade;
    protected int $dano;
    protected int $chance_esquiva;
    protected string $link_imagem;

    public function setAtribute($atribute, $value): mixed
    {
        return $this->$atribute = $value;
    }
    public function getAtribute($atribute): mixed
    {
        return $this->$atribute;
    }

    public function getVelocidade(): int
    {
        return $this->velocidade;
    }

    public function getAtributes(): array
    {
        return get_object_vars($this);
    }

    public function die() : void {
        $this->setAtribute("is_dead", true);
    }

    public function is_dead() : bool {
        return $this->is_dead;
    }

    public function take_damage(int $damage) : void {
        if(rand(0,100) <= $this->chance_esquiva){
            return;
        }

        $this->vida_atual -= $damage;

        if ($this->vida_atual <= 0){
            $this->die();
        }
    }

    public function getDano() : int {
        return $this->dano;
    }

    public function attack(AbsEntity $entity) : void {
        $entity->take_damage($this->getDano());
    }
    public function getNome(): string
    {
        return $this->nome;
    }

    public function cry(): string
    {
        return $this->nome . " chorou violentamente!";
    }
}