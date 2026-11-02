<?php
declare(strict_types=1);

require_once __DIR__ . '/Veiculo.php';
require_once __DIR__ . '/Aluno.php';

class Rota
{
    private string $nome;
    private Veiculo $veiculo;

    /** @var Aluno[] */
    private array $alunos = [];

    public function __construct(string $nome, Veiculo $veiculo)
    {
        $this->nome = $nome;
        $this->veiculo = $veiculo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getVeiculo(): Veiculo
    {
        return $this->veiculo;
    }

    public function getAlunos(): array
    {
        return $this->alunos;
    }

    public function adicionarAluno(Aluno $aluno): void
    {
        foreach ($this->alunos as $alunoAdicionado) {
            if ($alunoAdicionado->getMatricula() === $aluno->getMatricula()) {
                throw new Exception("Este aluno já está cadastrado na rota.");
            }
        }

        if (count($this->alunos) >= $this->veiculo->getCapacidade()) {
            throw new Exception("A capacidade máxima do veículo foi atingida.");
        }

        $this->alunos[] = $aluno;
    }

    public function calcularOcupacao(): string
    {
        return $this->veiculo->calcularLotacao(count($this->alunos));
    }

    public function paraArray(): array
    {
        return [
            "nome" => $this->nome,
            "veiculo" => $this->veiculo->paraArray(),
            "alunos" => array_map(
                fn(Aluno $aluno): array => $aluno->paraArray(),
                $this->alunos
            ),
            "ocupacao" => $this->calcularOcupacao()
        ];
    }
}