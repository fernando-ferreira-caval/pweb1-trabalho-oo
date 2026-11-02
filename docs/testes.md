# Testes

## Roteiro de testes no terminal

Comando: `php teste.php`

```text
=== POLIMORFISMO ===
Ônibus: 1/2 vagas ocupadas
Van: 1/8 vagas ocupadas

=== CADASTRO DE ALUNOS ===
Ana adicionada com sucesso.
Carlos adicionado com sucesso.

=== TESTE DE ALUNO DUPLICADO ===
Erro esperado: Este aluno já está cadastrado na rota.

=== TESTE DE CAPACIDADE ===
Erro esperado: A capacidade máxima do veículo foi atingida.

=== TESTE DE VEÍCULO INVÁLIDO ===
Erro esperado: A capacidade do veículo deve ser maior que zero.

=== DADOS DA ROTA ===
Array
(
    [nome] => Rota Centro
    [veiculo] => Array
        (
            [placa] => ABC-1234
            [modelo] => Mercedes-Benz
            [capacidade] => 2
            [tipo] => Ônibus
        )

    [alunos] => Array
        (
            [0] => Array
                (
                    [matricula] => 001
                    [nome] => Ana
                    [idade] => 5
                    [endereco] => Rua A
                )

            [1] => Array
                (
                    [matricula] => 002
                    [nome] => Carlos
                    [idade] => 4
                    [endereco] => Rua B
                )

        )

    [ocupacao] => Ônibus: 2/2 vagas ocupadas
)

Ocupação: Ônibus: 2/2 vagas ocupadas
```

**Explicação de cada linha:**

- **Polimorfismo:** o ônibus e a van executaram o método `calcularLotacao()`, apresentando resultados de acordo com suas capacidades.
- **Cadastro de alunos:** Ana e Carlos foram adicionados à rota com sucesso.
- **Aluno duplicado:** a tentativa de cadastrar novamente um aluno com a mesma matrícula foi recusada.
- **Capacidade:** a tentativa de adicionar um aluno acima da capacidade do veículo foi recusada.
- **Veículo inválido:** a criação de um veículo com capacidade zero foi impedida.
- **Dados da rota:** o método `paraArray()` retornou os dados da rota, do veículo e dos alunos.
- **Ocupação:** a rota identificou corretamente que as duas vagas disponíveis estavam ocupadas.

## Camada web

**Uma página que existe:** `curl -i "http://localhost:8000/?pagina=rotas"`

```text
HTTP/1.1 200 OK
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
...
</html>
```

O resultado HTTP 200 comprova que a página existe e foi carregada corretamente. A resposta também apresenta a rota Centro, seu veículo e a ocupação.

**Uma página que não existe:** `curl -i "http://localhost:8000/?pagina=inexistente"`

```text
HTTP/1.1 404 Not Found
Content-type: text/html; charset=UTF-8

<!DOCTYPE html>
<html lang="pt-BR">
...
<h2>Página não encontrada</h2>
<p>A página solicitada não existe.</p>
...
</html>
```

O resultado HTTP 404 comprova que o sistema identifica páginas inexistentes e apresenta uma mensagem informando que a página solicitada não foi encontrada.

**A rota JSON:** `curl -i "http://localhost:8000/?formato=json"`

```text
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{
    "rotas": {
        "centro": {
            "nome": "Rota Centro",
            "veiculo": {
                "placa": "ABC-1234",
                "modelo": "Mercedes-Benz",
                "capacidade": 20,
                "tipo": "Ônibus"
            },
            "alunos": [
                {
                    "matricula": "001",
                    "nome": "Ana",
                    "idade": 5,
                    "endereco": "Rua A"
                },
                {
                    "matricula": "002",
                    "nome": "Carlos",
                    "idade": 4,
                    "endereco": "Rua B"
                }
            ],
            "ocupacao": "Ônibus: 2\\/20 vagas ocupadas"
        }
    }
}
```

O resultado HTTP 200 comprova que a requisição foi processada corretamente. O cabeçalho `Content-Type` identifica a resposta como JSON. Os dados demonstram que o sistema consegue converter os objetos em uma estrutura JSON utilizando `paraArray()` e `json_encode()`.

**O formulário com um dado inválido:** envio de uma matrícula já cadastrada (`001`).

Comando:

```bash
curl -i -X POST "http://localhost:8000/?pagina=cadastro" -d "matricula=001&nome=Teste&idade=5&endereco=Rua+C"
```

```text
HTTP/1.1 200 OK
Content-type: text/html; charset=UTF-8

<p class="erro">Este aluno já está cadastrado na rota.</p>
```

O resultado comprova que o formulário recebe os dados por POST, identifica matrículas duplicadas e apresenta a mensagem de erro por meio do tratamento de exceções com `try/catch`.</textarea>

Inclua também um print da página mostrando a mensagem de recusa e o log do servidor.