<?php
declare(strict_types=1);

require_once __DIR__ . '/Veiculo.php';

class Onibus extends Veiculo
{
    public function __construct(
        string $placa,
        string $modelo,
        int $capacidade
    ) {
        parent::__construct($placa, $modelo, $capacidade);
    }

    public function calcularLotacao(int $quantidadeAlunos): string
    {
        return "Ônibus: " . parent::calcularLotacao($quantidadeAlunos);
    }

    public function paraArray(): array
    {
        return array_merge(
            parent::paraArray(),
            ["tipo" => "Ônibus"]
        );
    }
}