<?php
require_once "../Model/Itens/Equipamento.php";
class EquipamentoService
{

    private function getTabelaLv1(): array
    {
        return [
            new Equipamento("Bandana do Viajante", "cabeca", "Uma bandana simples que facilita os movimentos.", 5, 2, 0, 0),
            new Equipamento("Chapéu de Palha", "cabeca", "Um chapéu leve que protege contra o sol.", 8, 1, 1, 0),
            new Equipamento("Elmo de Couro", "cabeca", "Um elmo leve feito de couro endurecido.", 15, 0, 0, 2),
            new Equipamento("Máscara do Caçador", "cabeca", "Uma máscara utilizada por caçadores experientes.", 5, 3, 3, 0),
            new Equipamento("Capacete de Ferro", "cabeca", "Um capacete simples, mas resistente.", 20, -1, 0, 3),

            new Equipamento("Colete de Tecido", "peitoral", "Um colete simples e extremamente leve.", 12, 2, 0, 0),
            new Equipamento("Colete de Couro", "peitoral", "Uma proteção de couro utilizada por aventureiros.", 20, 1, 2, 1),
            new Equipamento("Cota de Malha", "peitoral", "Uma armadura formada por diversos anéis metálicos.", 30, -1, 0, 3),
            new Equipamento("Peitoral do Caçador", "peitoral", "Uma armadura leve feita para caçadores.", 25, 3, 3, 1),
            new Equipamento("Armadura de Bronze", "peitoral", "Uma armadura resistente feita de bronze.", 35, -1, 2, 4),

            new Equipamento("Sandálias do Viajante", "botas", "Sandálias simples que permitem caminhar com facilidade.", 0, 2, 0, 0),
            new Equipamento("Botas de Couro", "botas", "Botas resistentes feitas de couro.", 5, 2, 0, 1),
            new Equipamento("Botas do Caçador", "botas", "Botas leves utilizadas por caçadores.", 0, 4, 2, 0),
            new Equipamento("Botas Reforçadas", "botas", "Botas reforçadas para terrenos perigosos.", 8, 3, 0, 2),
            new Equipamento("Botas do Ladrão", "botas", "Botas leves feitas para movimentos rápidos.", 0, 6, 2, 1),

            new Equipamento("Adaga Enferrujada", "mao_principal", "Uma pequena adaga bastante desgastada.", 0, 1, 5, 0),
            new Equipamento("Clava de Madeira", "mao_principal", "Uma clava simples feita de madeira.", 0, -1, 7, 1),
            new Equipamento("Faca de Caçador", "mao_principal", "Uma faca utilizada por caçadores.", 0, 2, 8, 0),
            new Equipamento("Machado Pequeno", "mao_principal", "Um pequeno machado capaz de causar bons ferimentos.", 0, -1, 12, 0),
            new Equipamento("Lança Curta", "mao_principal", "Uma lança simples e equilibrada.", 0, 1, 11, 1),

            new Equipamento("Tábua de Madeira", "mao_secundaria", "Uma simples tábua que pode ser usada como escudo.", 5, 0, 0, 2),
            new Equipamento("Escudo de Couro", "mao_secundaria", "Um pequeno escudo coberto por couro.", 8, 0, 0, 3),
            new Equipamento("Broquel Reforçado", "mao_secundaria", "Um broquel reforçado para combates próximos.", 10, 1, 2, 3),
            new Equipamento("Escudo de Bronze", "mao_secundaria", "Um escudo resistente feito de bronze.", 15, -1, 0, 5),
            new Equipamento("Escudo do Soldado", "mao_secundaria", "Um escudo utilizado por soldados experientes.", 20, -1, 2, 5)
        ];
    }

    private function getTabelaLv2(): array
    {
        return [
            new Equipamento("Capuz Sombrio", "cabeca", "Um capuz escuro que favorece movimentos rápidos.", 10, 4, 2, 2),
            new Equipamento("Elmo do Soldado", "cabeca", "Elmo utilizado por soldados experientes.", 25, 0, 2, 4),
            new Equipamento("Máscara do Assassino", "cabeca", "Uma máscara leve utilizada por guerreiros furtivos.", 5, 5, 5, 1),
            new Equipamento("Elmo do Guardião", "cabeca", "Um pesado elmo utilizado por guardiões.", 30, -2, 0, 6),
            new Equipamento("Coroa de Cristal", "cabeca", "Uma coroa cristalina que emite uma energia misteriosa.", 20, 2, 6, 4),

            new Equipamento("Peitoral do Guerreiro", "peitoral", "Uma armadura equilibrada para combatentes.", 40, -1, 4, 4),
            new Equipamento("Armadura Escamada", "peitoral", "Peças sobrepostas oferecem boa proteção.", 45, -2, 2, 5),
            new Equipamento("Armadura do Explorador", "peitoral", "Uma armadura preparada para longas viagens.", 35, 3, 5, 3),
            new Equipamento("Peitoral Negro", "peitoral", "Uma armadura escura de origem desconhecida.", 40, 1, 7, 3),
            new Equipamento("Armadura de Cristal", "peitoral", "Cristais resistentes cobrem partes da armadura.", 50, -1, 6, 6),

            new Equipamento("Botas de Ferro", "botas", "Botas pesadas que protegem os pés.", 10, 1, 0, 4),
            new Equipamento("Botas do Vento", "botas", "Botas leves encantadas com a força dos ventos.", 0, 6, 0, 3),
            new Equipamento("Botas do Explorador", "botas", "Botas preparadas para longas jornadas.", 10, 5, 3, 2),
            new Equipamento("Botas Sombrias", "botas", "Botas envoltas por uma estranha energia.", 5, 8, 4, 2),
            new Equipamento("Botas do Corredor", "botas", "Botas criadas para alcançar grandes velocidades.", 0, 10, 2, 1),

            new Equipamento("Espada de Bronze", "mao_principal", "Uma espada resistente feita de bronze.", 0, 0, 15, 0),
            new Equipamento("Machado de Ferro", "mao_principal", "Um machado pesado e poderoso.", 0, -2, 19, 1),
            new Equipamento("Lança do Soldado", "mao_principal", "Uma lança utilizada por soldados experientes.", 0, 2, 17, 2),
            new Equipamento("Espada do Caçador", "mao_principal", "Uma espada leve feita para golpes rápidos.", 0, 3, 18, 1),
            new Equipamento("Machado de Guerra", "mao_principal", "Um enorme machado capaz de causar grandes danos.", 0, -3, 25, 0),

            new Equipamento("Broquel de Ferro", "mao_secundaria", "Um pequeno escudo feito de ferro.", 15, 1, 3, 5),
            new Equipamento("Escudo do Caçador", "mao_secundaria", "Um escudo leve que não atrapalha os movimentos.", 12, 3, 2, 4),
            new Equipamento("Escudo Reforçado", "mao_secundaria", "Um escudo pesado com diversas camadas de proteção.", 25, -2, 0, 7),
            new Equipamento("Escudo Espinhoso", "mao_secundaria", "Um escudo equipado com pequenas lâminas.", 20, -1, 7, 6),
            new Equipamento("Escudo Negro", "mao_secundaria", "Um escudo escuro coberto por uma energia misteriosa.", 25, 0, 5, 8)
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
        if ($level != "1" || $level != "2" || $level != "3") {
            $level = strval(rand(1,3));
        }

        $get = "getTabelaLv". $level;
        $table = $this->$get();
        return $table[array_rand($table)];
    }
}