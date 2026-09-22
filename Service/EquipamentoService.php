<?php
require_once "../Model/Itens/Equipamento.php";
class EquipamentoService
{

    private function getTabelaLv1(): array
    {
        return [
            new Equipamento("Bandana do Viajante", "cabeca", "Uma bandana simples que facilita os movimentos.", 6, 2, 0, 0),
            new Equipamento("Chapéu de Palha", "cabeca", "Um chapéu leve que protege contra o sol.", 10, 1, 1, 0),
            new Equipamento("Elmo de Couro", "cabeca", "Um elmo leve feito de couro endurecido.", 18, 0, 0, 2),
            new Equipamento("Máscara do Caçador", "cabeca", "Uma máscara utilizada por caçadores experientes.", 6, 4, 4, 0),
            new Equipamento("Capacete de Ferro", "cabeca", "Um capacete simples, mas resistente.", 24, -1, 0, 4),

            new Equipamento("Colete de Tecido", "peitoral", "Um colete simples e extremamente leve.", 14, 2, 0, 0),
            new Equipamento("Colete de Couro", "peitoral", "Uma proteção de couro utilizada por aventureiros.", 24, 1, 2, 1),
            new Equipamento("Cota de Malha", "peitoral", "Uma armadura formada por diversos anéis metálicos.", 36, -1, 0, 4),
            new Equipamento("Peitoral do Caçador", "peitoral", "Uma armadura leve feita para caçadores.", 30, 4, 4, 1),
            new Equipamento("Armadura de Bronze", "peitoral", "Uma armadura resistente feita de bronze.", 42, -1, 2, 5),

            new Equipamento("Sandálias do Viajante", "botas", "Sandálias simples que permitem caminhar com facilidade.", 0, 2, 0, 0),
            new Equipamento("Botas de Couro", "botas", "Botas resistentes feitas de couro.", 6, 2, 0, 1),
            new Equipamento("Botas do Caçador", "botas", "Botas leves utilizadas por caçadores.", 0, 5, 3, 4),
            new Equipamento("Botas Reforçadas", "botas", "Botas reforçadas para terrenos perigosos.", 10, 4, 0, 2),
            new Equipamento("Botas do Ladrão", "botas", "Botas leves feitas para movimentos rápidos.", 0, 7, 2, 6),

            new Equipamento("Adaga Enferrujada", "mao_principal", "Uma pequena adaga bastante desgastada.", 0, 1, 6, 0),
            new Equipamento("Clava de Madeira", "mao_principal", "Uma clava simples feita de madeira.", 0, -1, 8, 1),
            new Equipamento("Faca de Caçador", "mao_principal", "Uma faca utilizada por caçadores.", 0, 2, 10, 0),
            new Equipamento("Machado Pequeno", "mao_principal", "Um pequeno machado capaz de causar bons ferimentos.", 0, -1, 14, 0),
            new Equipamento("Lança Curta", "mao_principal", "Uma lança simples e equilibrada.", 0, 1, 13, 1),

            new Equipamento("Tábua de Madeira", "mao_secundaria", "Uma simples tábua que pode ser usada como escudo.", 6, 0, 0, 2),
            new Equipamento("Escudo de Couro", "mao_secundaria", "Um pequeno escudo coberto por couro.", 10, 0, 0, 4),
            new Equipamento("Broquel Reforçado", "mao_secundaria", "Um broquel reforçado para combates próximos.", 12, 1, 2, 4),
            new Equipamento("Escudo de Bronze", "mao_secundaria", "Um escudo resistente feito de bronze.", 18, -1, 0, 6),
            new Equipamento("Escudo do Soldado", "mao_secundaria", "Um escudo utilizado por soldados experientes.", 24, -1, 2, 6)
        ];
    }

    private function getTabelaLv2(): array
    {
        return [
            new Equipamento("Capuz Sombrio", "cabeca", "Um capuz escuro que favorece movimentos rápidos.", 12, 5, 2, 2),
            new Equipamento("Elmo do Soldado", "cabeca", "Elmo utilizado por soldados experientes.", 30, 0, 2, 5),
            new Equipamento("Máscara do Assassino", "cabeca", "Uma máscara leve utilizada por guerreiros furtivos.", 6, 6, 6, 1),
            new Equipamento("Elmo do Guardião", "cabeca", "Um pesado elmo utilizado por guardiões.", 36, -2, 0, 7),
            new Equipamento("Coroa de Cristal", "cabeca", "Uma coroa cristalina que emite uma energia misteriosa.", 24, 2, 7, 5),

            new Equipamento("Peitoral do Guerreiro", "peitoral", "Uma armadura equilibrada para combatentes.", 48, -1, 5, 5),
            new Equipamento("Armadura Escamada", "peitoral", "Peças sobrepostas oferecem boa proteção.", 54, -2, 2, 6),
            new Equipamento("Armadura do Explorador", "peitoral", "Uma armadura preparada para longas viagens.", 42, 4, 6, 4),
            new Equipamento("Peitoral Negro", "peitoral", "Uma armadura escura de origem desconhecida.", 48, 1, 8, 4),
            new Equipamento("Armadura de Cristal", "peitoral", "Cristais resistentes cobrem partes da armadura.", 60, -1, 7, 7),

            new Equipamento("Botas de Ferro", "botas", "Botas pesadas que protegem os pés.", 12, 1, 0, 5),
            new Equipamento("Botas do Vento", "botas", "Botas leves encantadas com a força dos ventos.", 0, 7, 0, 4),
            new Equipamento("Botas do Explorador", "botas", "Botas preparadas para longas jornadas.", 12, 6, 4, 2),
            new Equipamento("Botas Sombrias", "botas", "Botas envoltas por uma estranha energia.", 6, 10, 5, 2),
            new Equipamento("Botas do Corredor", "botas", "Botas criadas para alcançar grandes velocidades.", 0, 12, 2, 1),

            new Equipamento("Espada de Bronze", "mao_principal", "Uma espada resistente feita de bronze.", 0, 0, 18, 0),
            new Equipamento("Machado de Ferro", "mao_principal", "Um machado pesado e poderoso.", 0, -2, 23, 1),
            new Equipamento("Lança do Soldado", "mao_principal", "Uma lança utilizada por soldados experientes.", 0, 2, 20, 2),
            new Equipamento("Espada do Caçador", "mao_principal", "Uma espada leve feita para golpes rápidos.", 0, 4, 22, 1),
            new Equipamento("Machado de Guerra", "mao_principal", "Um enorme machado capaz de causar grandes danos.", 0, -4, 30, 0),

            new Equipamento("Broquel de Ferro", "mao_secundaria", "Um pequeno escudo feito de ferro.", 18, 1, 4, 6),
            new Equipamento("Escudo do Caçador", "mao_secundaria", "Um escudo leve que não atrapalha os movimentos.", 14, 4, 2, 5),
            new Equipamento("Escudo Reforçado", "mao_secundaria", "Um escudo pesado com diversas camadas de proteção.", 30, -2, 0, 8),
            new Equipamento("Escudo Espinhoso", "mao_secundaria", "Um escudo equipado com pequenas lâminas.", 24, -1, 8, 7),
            new Equipamento("Escudo Negro", "mao_secundaria", "Um escudo escuro coberto por uma energia misteriosa.", 30, 0, 6, 10)
        ];
    }

    private function getTabelaLv3(): array
    {
        return [
            new Equipamento("Capuz do Feiticeiro", "cabeca", "Um capuz imbuído com energia arcana.", 15, 3, 8, 2),
            new Equipamento("Elmo do Berserker", "cabeca", "Um elmo utilizado por guerreiros que buscam o combate.", 25, -1, 10, 3),
            new Equipamento("Máscara do Relâmpago", "cabeca", "Uma máscara que parece vibrar constantemente.", 10, 7, 5, 3),
            new Equipamento("Coroa do Herói", "cabeca", "Uma coroa que representa grandes feitos.", 30, 3, 8, 6),
            new Equipamento("Elmo do Campeão", "cabeca", "Um poderoso elmo utilizado por guerreiros lendários.", 45, 1, 10, 8),

            new Equipamento("Peitoral do Berserker", "peitoral", "Uma armadura pesada que favorece ataques agressivos.", 55, -2, 12, 2),
            new Equipamento("Armadura do Guardião", "peitoral", "Uma pesada armadura criada para proteger seus aliados.", 65, -3, 3, 9),
            new Equipamento("Armadura do Trovão", "peitoral", "Uma armadura energizada por forças elétricas.", 50, 3, 10, 6),
            new Equipamento("Armadura do Dragão", "peitoral", "Uma armadura extremamente resistente criada com materiais raros.", 75, -1, 12, 8),
            new Equipamento("Armadura do Titã", "peitoral", "Uma armadura colossal capaz de suportar golpes devastadores.", 90, -4, 15, 10),

            new Equipamento("Botas do Trovão", "botas", "Botas energizadas por pequenas descargas elétricas.", 5, 9, 6, 4),
            new Equipamento("Botas do Guardião", "botas", "Botas pesadas que aumentam a estabilidade do usuário.", 20, 4, 0, 7),
            new Equipamento("Botas Flamejantes", "botas", "Botas envoltas em uma chama constante.", 5, 10, 8, 4),
            new Equipamento("Botas Celestiais", "botas", "Botas extremamente leves que parecem desafiar a gravidade.", 10, 12, 6, 6),
            new Equipamento("Botas do Relâmpago", "botas", "Botas imbuídas com uma poderosa energia elétrica.", 0, 15, 8, 8),

            new Equipamento("Espada Flamejante", "mao_principal", "Uma espada envolta em chamas.", 0, 1, 27, 0),
            new Equipamento("Lança do Trovão", "mao_principal", "Uma lança que libera pequenas descargas elétricas.", 0, 4, 25, 2),
            new Equipamento("Machado do Berserker", "mao_principal", "Uma arma brutal criada para guerreiros agressivos.", 0, -2, 32, 0),
            new Equipamento("Espada Celestial", "mao_principal", "Uma espada que parece emitir uma luz própria.", 0, 2, 34, 3),
            new Equipamento("Lâmina do Campeão", "mao_principal", "Uma arma digna dos maiores guerreiros.", 0, 3, 40, 5),

            new Equipamento("Escudo Flamejante", "mao_secundaria", "Um escudo envolto em pequenas chamas.", 25, 1, 8, 8),
            new Equipamento("Escudo do Trovão", "mao_secundaria", "Um escudo que libera energia quando recebe impactos.", 30, 1, 7, 9),
            new Equipamento("Escudo do Guardião", "mao_secundaria", "Um enorme escudo criado para suportar ataques poderosos.", 40, -2, 5, 10),
            new Equipamento("Escudo Celestial", "mao_secundaria", "Um escudo feito de um material extremamente raro.", 45, 0, 8, 12),
            new Equipamento("Escudo do Campeão", "mao_secundaria", "Um escudo lendário capaz de resistir aos ataques mais poderosos.", 55, -2, 10, 15)
        ];
    }


    public function gerarEquipamento(string $level): \Equipamento
    {

        $get = "getTabelaLv". $level;
        $table = $this->$get();
        return $table[array_rand($table)];
    }
}