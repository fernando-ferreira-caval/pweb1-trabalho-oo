<?php
declare(strict_types=1);


require_once __DIR__ . '/../src/Veiculo.php';
require_once __DIR__ . '/../src/Onibus.php';
require_once __DIR__ . '/../src/Van.php';
require_once __DIR__ . '/../src/Aluno.php';
require_once __DIR__ . '/../src/Rota.php';


$arquivoDados = __DIR__ . '/../dados/sistema.json';

$salvarDados = static function (array $rotas) use ($arquivoDados): void {
    $dados = [];

    foreach ($rotas as $id => $rotaAtual) {
        $dados[$id] = $rotaAtual->paraArray();
    }

    $json = json_encode(
        ["rotas" => $dados],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );

    file_put_contents($arquivoDados, $json, LOCK_EX);
};

$carregarDados = static function () use ($arquivoDados): array {
    if (!is_file($arquivoDados)) {
        return [];
    }

    $conteudo = file_get_contents($arquivoDados);

    if ($conteudo === false) {
        throw new RuntimeException("Não foi possível ler os dados.");
    }

    $dados = json_decode($conteudo, true, 512, JSON_THROW_ON_ERROR);
    $rotas = [];

    foreach ($dados["rotas"] ?? [] as $id => $dadosRota) {
        $dadosVeiculo = $dadosRota["veiculo"];

        if ($dadosVeiculo["tipo"] === "Ônibus") {
            $veiculo = new Onibus(
                $dadosVeiculo["placa"],
                $dadosVeiculo["modelo"],
                $dadosVeiculo["capacidade"]
            );
        } elseif ($dadosVeiculo["tipo"] === "Van") {
            $veiculo = new Van(
                $dadosVeiculo["placa"],
                $dadosVeiculo["modelo"],
                $dadosVeiculo["capacidade"]
            );
        } else {
            throw new RuntimeException("Tipo de veículo inválido.");
        }

        $rotaAtual = new Rota($dadosRota["nome"], $veiculo);

        foreach ($dadosRota["alunos"] as $dadosAluno) {
            $aluno = new Aluno(
                $dadosAluno["matricula"],
                $dadosAluno["nome"],
                $dadosAluno["idade"],
                $dadosAluno["endereco"]
            );

            $rotaAtual->adicionarAluno($aluno);
        }

        $rotas[$id] = $rotaAtual;
    }

    return $rotas;
};

$rotas = $carregarDados();

if (!is_file($arquivoDados)) {
    $onibus = new Onibus("ABC-1234", "Mercedes-Benz", 20);
    $rota = new Rota("Rota Centro", $onibus);

    $rota->adicionarAluno(new Aluno("001", "Ana", 5, "Rua A"));
    $rota->adicionarAluno(new Aluno("002", "Carlos", 4, "Rua B"));

    $rotas = ["centro" => $rota];

    $salvarDados($rotas);
}

$rota = $rotas["centro"];


$mensagem = "";
$erro = "";

$pagina = $_GET["pagina"] ?? "inicio";
$idRota = $_GET["id"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $pagina === "cadastro") {
    try {
        $matricula = trim((string) ($_POST["matricula"] ?? ""));
        $nome = trim((string) ($_POST["nome"] ?? ""));
        $idade = filter_var($_POST["idade"] ?? null, FILTER_VALIDATE_INT);
        $endereco = trim((string) ($_POST["endereco"] ?? ""));

        if ($matricula === "" || $nome === "" || $endereco === "") {
            throw new Exception("Preencha todos os campos.");
        }

        if ($idade === false || $idade === null || $idade < 1) {
            throw new Exception("Informe uma idade válida.");
        }

        $novoAluno = new Aluno(
            $matricula,
            $nome,
            $idade,
            $endereco
        );

        $rota->adicionarAluno($novoAluno);
        $salvarDados($rotas);
        $mensagem = "Aluno cadastrado com sucesso!";

    } catch (Exception $excecao) {
        $erro = $excecao->getMessage();
    }
}


if (($_GET["formato"] ?? "") === "json") {
    header("Content-Type: application/json; charset=utf-8");

    $dados = [];

    foreach ($rotas as $id => $rotaAtual) {
        $dados[$id] = $rotaAtual->paraArray();
    }

    echo json_encode(
        ["rotas" => $dados],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    exit;
}


$paginasPermitidas = [
    "inicio",
    "rotas",
    "rota",
    "alunos",
    "cadastro"
];

if (!in_array($pagina, $paginasPermitidas, true)) {
    http_response_code(404);
    $pagina = "404";
}

$rotaSelecionada = null;

if ($pagina === "rota") {
    if (!isset($rotas[$idRota])) {
        http_response_code(404);
        $pagina = "404";
    } else {
        $rotaSelecionada = $rotas[$idRota];
    }
}


$esc = static function (string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
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
    <a href="?pagina=rotas" class="<?= $pagina === 'rotas' ? 'nav_ativo' : '' ?>">Rotas</a>
    <a href="?pagina=alunos" class="<?= $pagina === 'alunos' ? 'nav_ativo' : '' ?>">Alunos</a>
    <a href="?pagina=cadastro" class="<?= $pagina === 'cadastro' ? 'nav_ativo' : '' ?>">Cadastrar aluno</a>
</nav>
    </header>

  <main class="<?= $pagina === 'inicio' ? 'pagina_inicio' : ($pagina === 'cadastro' ? 'pagina_cadastro' : '') ?>">
        <?php if ($pagina === "inicio"): ?>

    <section class="card_inicio">
        <h2>Bem-vindo ao sistema</h2>

        <p>Gerencie as rotas e os alunos do transporte escolar.</p>

        <a href="?pagina=rotas">Visualizar rotas</a>
    </section>

        <?php elseif ($pagina === "rotas"): ?>

            <h2>Rotas cadastradas</h2>

            <?php foreach ($rotas as $id => $rotaAtual): ?>
                <section>
                    <h3><?= $esc($rotaAtual->getNome()) ?></h3>

                    <p>
                        Veículo:
                        <?= $esc($rotaAtual->getVeiculo()->getModelo()) ?>
                    </p>

                    <p>
                        Ocupação:
                        <?= $esc($rotaAtual->calcularOcupacao()) ?>
                    </p>

                    <a href="?pagina=rota&id=<?= $esc($id) ?>">
                        Ver detalhes
                    </a>
                </section>
            <?php endforeach; ?>

        <?php elseif ($pagina === "rota" && $rotaSelecionada !== null): ?>

            <h2><?= $esc($rotaSelecionada->getNome()) ?></h2>

            <h3>Veículo</h3>

            <p>
                Modelo:
                <?= $esc($rotaSelecionada->getVeiculo()->getModelo()) ?>
            </p>

            <p>
                Placa:
                <?= $esc($rotaSelecionada->getVeiculo()->getPlaca()) ?>
            </p>

            <p>
                Capacidade:
                <?= $esc((string) $rotaSelecionada->getVeiculo()->getCapacidade()) ?>
            </p>

            <p>
                Ocupação:
                <?= $esc($rotaSelecionada->calcularOcupacao()) ?>
            </p>

            <h3>Alunos da rota</h3>

            <?php foreach ($rotaSelecionada->getAlunos() as $aluno): ?>
                <section>
                    <p>
                        <?= $esc($aluno->getNome()) ?>
                        — Matrícula: <?= $esc($aluno->getMatricula()) ?>
                    </p>
                </section>
            <?php endforeach; ?>

        <?php elseif ($pagina === "alunos"): ?>

            <h2>Alunos cadastrados</h2>

            <?php foreach ($rota->getAlunos() as $aluno): ?>
                <section>
                    <h3><?= $esc($aluno->getNome()) ?></h3>

                    <p>
                        Matrícula:
                        <?= $esc($aluno->getMatricula()) ?>
                    </p>

                    <p>
                        Idade:
                        <?= $esc((string) $aluno->getIdade()) ?>
                    </p>

                    <p>
                        Endereço:
                        <?= $esc($aluno->getEndereco()) ?>
                    </p>
                </section>
            <?php endforeach; ?>

        <?php elseif ($pagina === "cadastro"): ?>

            <h2>Cadastrar aluno</h2>

            <?php if ($mensagem !== ""): ?>
                <p class="sucesso"><?= $esc($mensagem) ?></p>
            <?php endif; ?>

            <?php if ($erro !== ""): ?>
                <p class="erro"><?= $esc($erro) ?></p>
            <?php endif; ?>

            <form method="POST" action="?pagina=cadastro">

                <label for="matricula">Matrícula:</label>
                <input type="text" id="matricula" name="matricula" required>

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" min="1" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>

                <button type="submit">Cadastrar aluno</button>

            </form>

        <?php else: ?>

            <h2>Página não encontrada</h2>
            <p>A página solicitada não existe.</p>
            <a href="?pagina=inicio">Voltar ao início</a>

        <?php endif; ?>

    </main>

    <footer>
        <p>Sistema de Transporte Escolar</p>
    </footer>

</body>
</html>
