# Transporte Escolar

> Trabalho individual de Programação Web I (IFCE Campus Boa Viagem): sistema web orientado a objetos desenvolvido em PHP.

**Autor:** Fernando Everson Ferreira Cavalcante  
**Tema:** 14 - Transporte escolar  
**Vídeo:** _(cole aqui o link do vídeo)_

## O problema

O sistema Transporte Escolar foi desenvolvido para organizar informações sobre rotas, veículos e alunos do transporte escolar. Ele permite visualizar as rotas cadastradas, consultar os veículos e seus níveis de ocupação e cadastrar alunos associados a uma rota. O objetivo é facilitar o controle dessas informações em um único sistema.

## Como executar

Requisitos: PHP 8.3 ou superior.

```bash
git clone https://github.com/fernando-ferreira-caval/pweb1-trabalho-oo.git
cd pweb1-trabalho-oo
php -S localhost:8000 -t public -d display_errors=1 -d output_buffering=0
```

Abra [http://localhost:8000](http://localhost:8000).

Para rodar o roteiro de testes no terminal:

```bash
php teste.php
```

Os dados cadastrados são armazenados no arquivo `dados/sistema.json`.

## Telas

Adicione 2 ou 3 capturas de tela na pasta `docs/` e utilize os caminhos abaixo:

![Tela inicial](docs/imagem1.png)

![Tela de cadastro](docs/imagem2.png)

![Tela dos alunos cadastrados](docs/imagem3.png)

## Rotas

| URL | O que mostra | Método | Status |
|---|---|---|---|
| `/` | Página inicial | GET | 200 |
| `/?pagina=inicio` | Página inicial | GET | 200 |
| `/?pagina=rotas` | Lista de rotas cadastradas | GET | 200 |
| `/?pagina=rota&id=centro` | Detalhes da rota Centro | GET | 200 |
| `/?pagina=alunos` | Lista de alunos cadastrados | GET | 200 |
| `/?pagina=cadastro` | Formulário de cadastro de alunos | GET | 200 |
| `/?pagina=inexistente` | Página não encontrada | GET | 404 |
| `/?formato=json` | Dados das rotas em JSON | GET | 200 |
| `/?pagina=cadastro` | Envio do formulário de cadastro | POST | 200 |

## Regras de negócio

| Nº | Regra | Classe e método | Mensagem da exceção |
|---|---|---|---|
| 1 | A capacidade do veículo deve ser maior que zero. | `Veiculo::__construct()` | A capacidade do veículo deve ser maior que zero. |
| 2 | Um aluno não pode ser cadastrado duas vezes na mesma rota. | `Rota::adicionarAluno()` | Este aluno já está cadastrado na rota. |
| 3 | A quantidade de alunos não pode ultrapassar a capacidade do veículo. | `Rota::adicionarAluno()` | A capacidade máxima do veículo foi atingida. |

## Documentação

- [Modelagem (diagrama de classes)](docs/modelagem.md)
- [Testes](docs/testes.md)
- [Diário de uso de LLM](docs/llm.md)
- [Relatório (PDF)](docs/relatorio.pdf)

## Ferramentas utilizadas

| Ferramenta | Para que usei |
|---|---|
| PHP 8.3 ou superior | Desenvolvimento da lógica e das classes do sistema. |
| VS Code | Escrita e edição do código. |
| Git e GitHub | Versionamento e armazenamento do projeto. |
| curl | Testes de requisições HTTP e respostas do sistema. |
| Navegador e DevTools | Acesso ao sistema e verificação das páginas. |
| LLM: ChatGPT | Apoio durante o desenvolvimento, esclarecimento de dúvidas e identificação de erros. |
| Gravador de tela: _(preencher)_ | Gravação do vídeo de apresentação do projeto. |
