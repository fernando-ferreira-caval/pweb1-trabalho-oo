<?php
declare(strict_types=1);

class Veiculo
{
    private string $placa;
    private string $modelo;
    private int $capacidade;

    public function __construct(
        string $placa,
        string $modelo,
        int $capacidade
    ) {
        if ($capacidade < 1) {
            throw new Exception("A capacidade do veículo deve ser maior que zero.");
        }

        $this->placa = $placa;
        $this->modelo = $modelo;
        $this->capacidade = $capacidade;
    }

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }

    public function getCapacidade(): int
    {
        return $this->capacidade;
    }

    public function calcularLotacao(int $quantidadeAlunos): string
    {
        return "{$quantidadeAlunos}/{$this->capacidade} vagas ocupadas";
    }

    public function paraArray(): array
{
    return [
        "placa" => $this->placa,
        "modelo" => $this->modelo,
        "capacidade" => $this->capacidade
    ];
}
}
