<?php
class Inimigo extends AbsEntity
{
    private array $drops;
    public function __construct(string $nome, int $vida_maxima, int $velocidade, int $dano, int $chance_esquiva, array $drops, string $link_imagem)
    {
        $this->nome = $nome;
        $this->vida_maxima = $vida_maxima;
        $this->vida_atual = $vida_maxima;
        $this->velocidade = $velocidade;
        $this->dano = $dano;
        $this->chance_esquiva = $chance_esquiva;
        $this->drops = $drops;
        $this->link_imagem = $link_imagem;
    }

    public function get_loot(): array
    {
        return $this->drops;
    }

    public function attack(AbsEntity $entity) : void {
        AnimationService::acaoAtacar("inimigo");
        parent::attack($entity);
    }
    public function take_damage(int $damage): void
    {
        AnimationService::acaoDano("inimigo", $damage);
        parent::take_damage($damage);
    }
    public function getLinkImagem(): string
    {
        return $this->link_imagem;
    }
}