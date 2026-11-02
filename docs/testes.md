
# Testes do sistema Transporte Escolar

## Objetivo

Verificar o funcionamento das classes, das regras de negócio, das páginas web, dos formulários e da persistência dos dados.

## 1. Testes das classes

O arquivo `teste.php` contém testes de polimorfismo, cadastro de alunos, capacidade dos veículos e conversão de objetos para arrays.

Comando:

```bash
php teste.php
```

### Polimorfismo

O teste cria um ônibus com capacidade para dois alunos e uma van com capacidade para oito alunos.

Os dois objetos são colocados em um array e percorrem um `foreach`, que chama `calcularLotacao()`.

Saída esperada:

```text
=== POLIMORFISMO ===
Ônibus: 1/2 vagas ocupadas
Van: 1/8 vagas ocupadas
```

### Cadastro de alunos

O teste adiciona dois alunos à rota Centro.

Saída esperada:

```text
=== CADASTRO DE ALUNOS ===
Ana adicionada com sucesso.
Carlos adicionado com sucesso.
```

### Matrícula duplicada

O teste tenta adicionar novamente um aluno com matrícula já existente na rota.

Saída esperada:

```text
=== TESTE DE ALUNO DUPLICADO ===
Erro esperado: Este aluno já está cadastrado na rota.
```

### Limite de capacidade

O teste tenta adicionar um terceiro aluno a um ônibus com capacidade para dois.

Saída esperada:

```text
=== TESTE DE CAPACIDADE ===
Erro esperado: A capacidade máxima do veículo foi atingida.
```

### Capacidade inválida

O teste tenta criar uma van com capacidade zero.

Saída esperada:

```text
=== TESTE DE VEÍCULO INVÁLIDO ===
Erro esperado: A capacidade do veículo deve ser maior que zero.
```

### Conversão para array

O teste chama `Rota::paraArray()` e apresenta os dados da rota, do veículo e dos alunos.

A ocupação final esperada é:

```text
Ocupação: Ônibus: 2/2 vagas ocupadas
```

## 2. Testes HTTP

Inicie o servidor:

```bash
php -S localhost:8000 -t public
```

### Página existente

```bash
curl -i "http://localhost:8000/?pagina=rotas"
```

Resultado esperado: HTTP 200 e conteúdo HTML com as rotas cadastradas.

### Página inexistente

```bash
curl -i "http://localhost:8000/?pagina=inexistente"
```

Resultado esperado: HTTP 404 e mensagem `Página não encontrada`.

### Identificador de rota inexistente

```bash
curl -i "http://localhost:8000/?pagina=rota&id=inexistente"
```

Resultado esperado: HTTP 404.

### Resposta JSON

```bash
curl -i "http://localhost:8000/?formato=json"
```

Resultado esperado:

```text
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
```

O corpo deve conter as coleções `veiculos` e `rotas`.

## 3. Testes dos formulários

Os testes abaixo utilizam o navegador e devem ser realizados com dados fictícios.

| Teste | Procedimento | Resultado esperado |
|---|---|---|
| Cadastro de ônibus | Informar placa, modelo e capacidade válidos | Veículo cadastrado |
| Cadastro de van | Selecionar Van e preencher os campos | Veículo cadastrado |
| Placa duplicada | Repetir uma placa cadastrada | Mensagem de erro |
| Capacidade zero | Enviar capacidade igual a zero | Cadastro recusado |
| Cadastro de rota | Informar nome e selecionar veículo | Rota cadastrada |
| Nome de rota repetido | Cadastrar outra rota com o mesmo nome | Mensagem de erro |
| Veículo ocupado | Associar um veículo já utilizado a outra rota | Cadastro recusado |
| Cadastro de aluno | Preencher dados e selecionar rota | Aluno cadastrado |
| Matrícula repetida | Reutilizar matrícula existente | Cadastro recusado |
| Capacidade esgotada | Adicionar aluno a uma rota lotada | Cadastro recusado |

## 4. Persistência

Procedimento:

1. Cadastrar um veículo.
2. Cadastrar uma rota utilizando esse veículo.
3. Cadastrar um aluno na rota.
4. Encerrar o servidor PHP.
5. Iniciar o servidor novamente.
6. Consultar as páginas de veículos, rotas e alunos.

Resultado esperado: os cadastros continuam disponíveis após reiniciar o servidor.

O armazenamento utiliza `dados/sistema.json`.

## 5. Testes de segurança e validação

### Dados HTML

Cadastrar um nome contendo caracteres especiais, como:

```text
Ana & Carlos
```

Resultado esperado: o texto aparece corretamente no HTML, sem ser interpretado como código.

### Dados numéricos

Informar valores inválidos para idade ou capacidade.

Resultado esperado: o sistema recusa valores que não atendem às regras.

### Seleção de rota

Tentar enviar um identificador de rota inexistente.

Resultado esperado: o cadastro é recusado.

## 6. Evidências

Registrar capturas de tela das seguintes situações:

- Página de veículos cadastrados.
- Formulário de cadastro de veículo.
- Página de rotas cadastradas.
- Formulário de cadastro de rota.
- Formulário de cadastro de aluno.
- Mensagem de erro de validação.
- Resposta JSON.
- Resultado do comando `php teste.php`.

## 7. Situação dos testes

O roteiro de testes das classes já foi utilizado durante o desenvolvimento.

Após a ampliação do sistema, foram verificados manualmente os cadastros de veículos, rotas e alunos, conforme os testes realizados no navegador.

Os comandos HTTP e os casos de validação descritos neste documento também servem como roteiro de regressão para novas alterações.

Os resultados esperados não devem ser confundidos com evidências de execução: cada resultado precisa ser confirmado antes de ser apresentado como teste realizado.
