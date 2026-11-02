<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Veiculo.php';
require_once __DIR__ . '/../src/Onibus.php';
require_once __DIR__ . '/../src/Van.php';
require_once __DIR__ . '/../src/Aluno.php';
require_once __DIR__ . '/../src/Rota.php';

$arquivoDados = __DIR__ . '/../dados/sistema.json';

$criarVeiculo = static function (array $dados): Veiculo {
    if ($dados['tipo'] === 'Ônibus') {
        return new Onibus($dados['placa'], $dados['modelo'], (int) $dados['capacidade']);
    }
    if ($dados['tipo'] === 'Van') {
        return new Van($dados['placa'], $dados['modelo'], (int) $dados['capacidade']);
    }
    throw new Exception('Tipo de veículo inválido.');
};

$carregarDados = static function () use ($arquivoDados, $criarVeiculo): array {
    if (!is_file($arquivoDados)) {
        $onibus = new Onibus('ABC-1234', 'Mercedes-Benz', 20);
        $rota = new Rota('Rota Centro', $onibus);
        $rota->adicionarAluno(new Aluno('001', 'Ana', 5, 'Rua A'));
        $rota->adicionarAluno(new Aluno('002', 'Carlos', 4, 'Rua B'));
        return [['ABC-1234' => $onibus], ['centro' => $rota]];
    }

    $conteudo = file_get_contents($arquivoDados);
    if ($conteudo === false) {
        throw new RuntimeException('Não foi possível ler os dados.');
    }
    $dados = json_decode($conteudo, true, 512, JSON_THROW_ON_ERROR);
    $veiculos = [];
    $rotas = [];

    foreach ($dados['veiculos'] ?? [] as $placa => $dadosVeiculo) {
        $veiculos[$placa] = $criarVeiculo($dadosVeiculo);
    }

    foreach ($dados['rotas'] ?? [] as $id => $dadosRota) {
        $dadosVeiculo = $dadosRota['veiculo'];
        $placa = $dadosVeiculo['placa'];
        if (!isset($veiculos[$placa])) {
            $veiculos[$placa] = $criarVeiculo($dadosVeiculo);
        }
        $rota = new Rota($dadosRota['nome'], $veiculos[$placa]);
        foreach ($dadosRota['alunos'] ?? [] as $dadosAluno) {
            $rota->adicionarAluno(new Aluno(
                $dadosAluno['matricula'],
                $dadosAluno['nome'],
                (int) $dadosAluno['idade'],
                $dadosAluno['endereco']
            ));
        }
        $rotas[$id] = $rota;
    }
    return [$veiculos, $rotas];
};

$salvarDados = static function (array $veiculos, array $rotas) use ($arquivoDados): void {
    $dadosVeiculos = [];
    $dadosRotas = [];
    foreach ($veiculos as $placa => $veiculo) {
        $dadosVeiculos[$placa] = $veiculo->paraArray();
    }
    foreach ($rotas as $id => $rota) {
        $dadosRotas[$id] = $rota->paraArray();
    }
    $json = json_encode(
        ['veiculos' => $dadosVeiculos, 'rotas' => $dadosRotas],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );
    if (file_put_contents($arquivoDados, $json, LOCK_EX) === false) {
        throw new RuntimeException('Não foi possível salvar os dados.');
    }
};

[$veiculos, $rotas] = $carregarDados();
if (!is_file($arquivoDados)) {
    $salvarDados($veiculos, $rotas);
}

$pagina = (string) ($_GET['pagina'] ?? 'inicio');
$idRota = (string) ($_GET['id'] ?? '');
$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($pagina === 'cadastro-veiculo') {
            $tipo = trim((string) ($_POST['tipo'] ?? ''));
            $placa = strtoupper(trim((string) ($_POST['placa'] ?? '')));
            $modelo = trim((string) ($_POST['modelo'] ?? ''));
            $capacidade = filter_var($_POST['capacidade'] ?? null, FILTER_VALIDATE_INT);
            if ($placa === '' || $modelo === '' || !in_array($tipo, ['Ônibus', 'Van'], true)) {
                throw new Exception('Preencha os dados do veículo corretamente.');
            }
            if ($capacidade === false || $capacidade === null) {
                throw new Exception('Informe uma capacidade válida.');
            }
            if (isset($veiculos[$placa])) {
                throw new Exception('Já existe um veículo com esta placa.');
            }
            $novoVeiculo = $criarVeiculo(['tipo' => $tipo, 'placa' => $placa, 'modelo' => $modelo, 'capacidade' => $capacidade]);
            $veiculos[$placa] = $novoVeiculo;
            $salvarDados($veiculos, $rotas);
            $mensagem = 'Veículo cadastrado com sucesso!';
        } elseif ($pagina === 'cadastro-rota') {
            $nome = trim((string) ($_POST['nome'] ?? ''));
            $placa = trim((string) ($_POST['placa'] ?? ''));
            if ($nome === '' || !isset($veiculos[$placa])) {
                throw new Exception('Informe o nome e selecione um veículo válido.');
            }
            foreach ($rotas as $rotaAtual) {
                if (mb_strtolower($rotaAtual->getNome()) === mb_strtolower($nome)) {
                    throw new Exception('Já existe uma rota com este nome.');
                }
                if ($rotaAtual->getVeiculo()->getPlaca() === $placa) {
                    throw new Exception('Este veículo já está associado a uma rota.');
                }
            }
            $id = 'rota-' . bin2hex(random_bytes(4));
            $rotas[$id] = new Rota($nome, $veiculos[$placa]);
            $salvarDados($veiculos, $rotas);
            $mensagem = 'Rota cadastrada com sucesso!';
        } elseif ($pagina === 'cadastro') {
            $matricula = trim((string) ($_POST['matricula'] ?? ''));
            $nome = trim((string) ($_POST['nome'] ?? ''));
            $idade = filter_var($_POST['idade'] ?? null, FILTER_VALIDATE_INT);
            $endereco = trim((string) ($_POST['endereco'] ?? ''));
            $id = (string) ($_POST['rota'] ?? '');
            if ($matricula === '' || $nome === '' || $endereco === '') {
                throw new Exception('Preencha todos os campos.');
            }
            if ($idade === false || $idade === null || $idade < 1) {
                throw new Exception('Informe uma idade válida.');
            }
            if (!isset($rotas[$id])) {
                throw new Exception('Selecione uma rota válida.');
            }
            foreach ($rotas as $rotaAtual) {
                foreach ($rotaAtual->getAlunos() as $alunoAtual) {
                    if ($alunoAtual->getMatricula() === $matricula) {
                        throw new Exception('Este aluno já está cadastrado no sistema.');
                    }
                }
            }
            $rotas[$id]->adicionarAluno(new Aluno($matricula, $nome, $idade, $endereco));
            $salvarDados($veiculos, $rotas);
            $mensagem = 'Aluno cadastrado com sucesso!';
        }
    } catch (Exception $excecao) {
        $erro = $excecao->getMessage();
    }
}

if (($_GET['formato'] ?? '') === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    $dadosVeiculos = [];
    $dadosRotas = [];
    foreach ($veiculos as $placa => $veiculo) {
        $dadosVeiculos[$placa] = $veiculo->paraArray();
    }
    foreach ($rotas as $id => $rota) {
        $dadosRotas[$id] = $rota->paraArray();
    }
    echo json_encode(['veiculos' => $dadosVeiculos, 'rotas' => $dadosRotas], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$paginasPermitidas = ['inicio', 'veiculos', 'cadastro-veiculo', 'rotas', 'cadastro-rota', 'rota', 'alunos', 'cadastro'];
if (!in_array($pagina, $paginasPermitidas, true)) {
    http_response_code(404);
    $pagina = '404';
}
$rotaSelecionada = null;
if ($pagina === 'rota') {
    if (!isset($rotas[$idRota])) {
        http_response_code(404);
        $pagina = '404';
    } else {
        $rotaSelecionada = $rotas[$idRota];
    }
}
$esc = static function (string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
};
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transporte Escolar</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<header>
    <h1>Transporte Escolar</h1>
    <nav>
        <a href="?pagina=inicio" class="<?= $pagina === 'inicio' ? 'nav_ativo' : '' ?>">Início</a>
        <a href="?pagina=veiculos" class="<?= in_array($pagina, ['veiculos', 'cadastro-veiculo'], true) ? 'nav_ativo' : '' ?>">Veículos</a>
        <a href="?pagina=rotas" class="<?= in_array($pagina, ['rotas', 'rota', 'cadastro-rota'], true) ? 'nav_ativo' : '' ?>">Rotas</a>
        <a href="?pagina=alunos" class="<?= $pagina === 'alunos' ? 'nav_ativo' : '' ?>">Alunos</a>
        <a href="?pagina=cadastro" class="<?= $pagina === 'cadastro' ? 'nav_ativo' : '' ?>">Cadastrar aluno</a>
    </nav>
</header>
<main class="<?= $pagina === 'inicio' ? 'pagina_inicio' : (in_array($pagina, ['cadastro', 'cadastro-veiculo', 'cadastro-rota'], true) ? 'pagina_cadastro' : '') ?>">
<?php if ($pagina === 'inicio'): ?>
    <section class="card_inicio">
        <h2>Bem-vindo ao sistema</h2>
        <p>Gerencie os veículos, as rotas e os alunos do transporte escolar.</p>
        <a href="?pagina=rotas">Visualizar rotas</a>
    </section>
<?php elseif ($pagina === 'veiculos'): ?>
    <h2>Veículos cadastrados</h2>
    <a class="link_acao" href="?pagina=cadastro-veiculo">Cadastrar veículo</a>
    <?php if ($veiculos === []): ?><p>Nenhum veículo cadastrado.</p><?php endif; ?>
    <?php foreach ($veiculos as $veiculo): ?>
        <section>
            <h3><?= $esc($veiculo->getModelo()) ?></h3>
            <p>Tipo: <?= $esc($veiculo->paraArray()['tipo']) ?></p>
            <p>Placa: <?= $esc($veiculo->getPlaca()) ?></p>
            <p>Capacidade: <?= $esc((string) $veiculo->getCapacidade()) ?></p>
        </section>
    <?php endforeach; ?>
<?php elseif ($pagina === 'rotas'): ?>
    <h2>Rotas cadastradas</h2>
    <a class="link_acao" href="?pagina=cadastro-rota">Cadastrar rota</a>
    <?php if ($rotas === []): ?><p>Nenhuma rota cadastrada.</p><?php endif; ?>
    <?php foreach ($rotas as $id => $rotaAtual): ?>
        <section>
            <h3><?= $esc($rotaAtual->getNome()) ?></h3>
            <p>Veículo: <?= $esc($rotaAtual->getVeiculo()->getModelo()) ?></p>
            <p>Ocupação: <?= $esc($rotaAtual->calcularOcupacao()) ?></p>
            <a href="?pagina=rota&amp;id=<?= $esc((string) $id) ?>">Ver detalhes</a>
        </section>
    <?php endforeach; ?>
<?php elseif ($pagina === 'rota' && $rotaSelecionada !== null): ?>
    <h2><?= $esc($rotaSelecionada->getNome()) ?></h2>
    <h3>Veículo</h3>
    <p>Modelo: <?= $esc($rotaSelecionada->getVeiculo()->getModelo()) ?></p>
    <p>Placa: <?= $esc($rotaSelecionada->getVeiculo()->getPlaca()) ?></p>
    <p>Capacidade: <?= $esc((string) $rotaSelecionada->getVeiculo()->getCapacidade()) ?></p>
    <p>Ocupação: <?= $esc($rotaSelecionada->calcularOcupacao()) ?></p>
    <h3>Alunos da rota</h3>
    <?php if ($rotaSelecionada->getAlunos() === []): ?><p>Nenhum aluno nesta rota.</p><?php endif; ?>
    <?php foreach ($rotaSelecionada->getAlunos() as $aluno): ?>
        <section><p><?= $esc($aluno->getNome()) ?> — Matrícula: <?= $esc($aluno->getMatricula()) ?></p></section>
    <?php endforeach; ?>
<?php elseif ($pagina === 'alunos'): ?>
    <h2>Alunos cadastrados</h2>
    <?php foreach ($rotas as $rotaAtual): ?>
        <?php foreach ($rotaAtual->getAlunos() as $aluno): ?>
            <section>
                <h3><?= $esc($aluno->getNome()) ?></h3>
                <p>Matrícula: <?= $esc($aluno->getMatricula()) ?></p>
                <p>Idade: <?= $esc((string) $aluno->getIdade()) ?></p>
                <p>Endereço: <?= $esc($aluno->getEndereco()) ?></p>
                <p>Rota: <?= $esc($rotaAtual->getNome()) ?></p>
            </section>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php elseif (in_array($pagina, ['cadastro', 'cadastro-veiculo', 'cadastro-rota'], true)): ?>
    <h2><?= $pagina === 'cadastro' ? 'Cadastrar aluno' : ($pagina === 'cadastro-veiculo' ? 'Cadastrar veículo' : 'Cadastrar rota') ?></h2>
    <?php if ($mensagem !== ''): ?><p class="sucesso"><?= $esc($mensagem) ?></p><?php endif; ?>
    <?php if ($erro !== ''): ?><p class="erro"><?= $esc($erro) ?></p><?php endif; ?>
    <form method="POST" action="?pagina=<?= $esc($pagina) ?>">
    <?php if ($pagina === 'cadastro-veiculo'): ?>
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required><option value="Ônibus">Ônibus</option><option value="Van">Van</option></select>
        <label for="placa">Placa:</label>
        <input type="text" id="placa" name="placa" required>
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required>
        <label for="capacidade">Capacidade:</label>
        <input type="number" id="capacidade" name="capacidade" min="1" required>
        <button type="submit">Cadastrar veículo</button>
    <?php elseif ($pagina === 'cadastro-rota'): ?>
        <label for="nome">Nome da rota:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="placa">Veículo:</label>
        <select id="placa" name="placa" required>
            <option value="">Selecione um veículo</option>
            <?php foreach ($veiculos as $veiculo): ?>
                <option value="<?= $esc($veiculo->getPlaca()) ?>"><?= $esc($veiculo->getModelo() . ' - ' . $veiculo->getPlaca()) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cadastrar rota</button>
    <?php else: ?>
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade" min="1" required>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        <label for="rota">Rota:</label>
        <select id="rota" name="rota" required>
            <option value="">Selecione uma rota</option>
            <?php foreach ($rotas as $id => $rotaAtual): ?>
                <option value="<?= $esc((string) $id) ?>"><?= $esc($rotaAtual->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cadastrar aluno</button>
    <?php endif; ?>
    </form>
<?php else: ?>
    <h2>Página não encontrada</h2>
    <p>A página solicitada não existe.</p>
    <a href="?pagina=inicio">Voltar ao início</a>
<?php endif; ?>
</main>
<footer><p>Sistema de Transporte Escolar</p></footer>
</body>
</html>
