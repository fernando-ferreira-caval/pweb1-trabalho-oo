<?php
declare(strict_types=1);

require_once __DIR__ . '/src/Veiculo.php';
require_once __DIR__ . '/src/Onibus.php';
require_once __DIR__ . '/src/Van.php';
require_once __DIR__ . '/src/Aluno.php';
require_once __DIR__ . '/src/Rota.php';

$onibus = new Onibus("ABC-1234", "Mercedes-Benz", 2);
$van = new Van("DEF-5678", "Fiat Ducato", 8);

echo "=== POLIMORFISMO ===\n";

$veiculos = [$onibus, $van];

foreach ($veiculos as $veiculo) {
    echo $veiculo->calcularLotacao(1) . "\n";
}

$aluno1 = new Aluno("001", "Ana", 5, "Rua A");
$aluno2 = new Aluno("002", "Carlos", 4, "Rua B");
$aluno3 = new Aluno("003", "Mariana", 5, "Rua C");

$rota = new Rota("Rota Centro", $onibus);

echo "\n=== CADASTRO DE ALUNOS ===\n";

try {
    $rota->adicionarAluno($aluno1);
    echo "Ana adicionada com sucesso.\n";

    $rota->adicionarAluno($aluno2);
    echo "Carlos adicionado com sucesso.\n";

} catch (Exception $erro) {
    echo "Erro: " . $erro->getMessage() . "\n";
}

echo "\n=== TESTE DE ALUNO DUPLICADO ===\n";

try {
    $rota->adicionarAluno($aluno1);
} catch (Exception $erro) {
    echo "Erro esperado: " . $erro->getMessage() . "\n";
}

echo "\n=== TESTE DE CAPACIDADE ===\n";

try {
    $rota->adicionarAluno($aluno3);
} catch (Exception $erro) {
    echo "Erro esperado: " . $erro->getMessage() . "\n";
}

echo "\n=== TESTE DE VEÍCULO INVÁLIDO ===\n";

try {
    $veiculoInvalido = new Van("GHI-9012", "Fiat Ducato", 0);
} catch (Exception $erro) {
    echo "Erro esperado: " . $erro->getMessage() . "\n";
}

echo "\n=== DADOS DA ROTA ===\n";

print_r($rota->paraArray());

echo "\nOcupação: " . $rota->calcularOcupacao() . "\n";
