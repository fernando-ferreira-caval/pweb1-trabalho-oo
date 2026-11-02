<?php
declare(strict_types=1);

require_once __DIR__ . '/Veiculo.php';

class Van extends Veiculo
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
        return "Van: " . parent::calcularLotacao($quantidadeAlunos);
    }

    public function paraArray(): array
    {
        return array_merge(
            parent::paraArray(),
            ["tipo" => "Van"]
        );
    }
}