<?php
declare(strict_types=1);

class Aluno
{
    private string $matricula;
    private string $nome;
    private int $idade;
    private string $endereco;

    public function __construct(
        string $matricula,
        string $nome,
        int $idade,
        string $endereco
    ) {
        $this->matricula = $matricula;
        $this->nome = $nome;
        $this->idade = $idade;
        $this->endereco = $endereco;
    }

    public function getMatricula(): string
    {
        return $this->matricula;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getIdade(): int
    {
        return $this->idade;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function paraArray(): array
    {
        return [
            "matricula" => $this->matricula,
            "nome" => $this->nome,
            "idade" => $this->idade,
            "endereco" => $this->endereco
        ];
    }
}