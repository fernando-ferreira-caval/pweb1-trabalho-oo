
# Diário de uso de LLM

## Ferramenta utilizada

ChatGPT, da OpenAI.

## Finalidade

A ferramenta foi utilizada como apoio durante o desenvolvimento do sistema Transporte Escolar, principalmente na organização das classes, compreensão dos conceitos de orientação a objetos, identificação de erros, implementação das funcionalidades web e elaboração da documentação.

As sugestões foram analisadas e testadas antes de serem incorporadas ao projeto.

## Interação 1 — Modelagem

**Período:** outubro de 2026.

**Solicitação:** apoio para organizar as classes de um sistema de transporte escolar e aplicar herança, composição, encapsulamento e polimorfismo.

**Resposta:** orientação para estruturar as classes `Veiculo`, `Onibus`, `Van`, `Aluno` e `Rota`.

**Decisão:** a estrutura foi utilizada por representar as entidades principais do sistema e atender aos requisitos de orientação a objetos.

## Interação 2 — Implementação das classes

**Período:** outubro de 2026.

**Solicitação:** apoio para implementar classes PHP com atributos privados, construtores, métodos, herança e regras de negócio.

**Resposta:** sugestões de organização dos arquivos e implementação dos métodos.

**Decisão:** as classes foram implementadas em arquivos separados e verificadas com o roteiro `teste.php`.

## Interação 3 — Interface

**Período:** outubro de 2026.

**Solicitação:** apoio para organizar a interface do sistema com HTML e CSS.

**Resposta:** orientações sobre cabeçalho, navegação, formulários, espaçamento e apresentação das informações.

**Decisão:** os ajustes foram avaliados visualmente no navegador.

## Interação 4 — Persistência

**Período:** outubro e novembro de 2026.

**Solicitação:** implementar armazenamento para que os dados cadastrados não fossem perdidos ao atualizar a página ou reiniciar o servidor.

**Resposta:** orientação para utilizar `file_get_contents()`, `json_decode()`, `json_encode()` e `file_put_contents()`.

**Decisão:** foi adotado um arquivo JSON para armazenar os dados do sistema, evitando adicionar um banco de dados à aplicação.

## Interação 5 — Ampliação dos cadastros

**Data:** 2 de novembro de 2026.

**Solicitação:** adicionar cadastro de veículos e rotas, preservando a estrutura visual já existente.

**Resposta:** proposta de implementação dos formulários, associação de veículos às rotas, seleção de rota no cadastro de alunos e adaptação da persistência.

**Decisão:** as alterações foram aplicadas ao `public/index.php`, com um pequeno complemento ao CSS existente.

## Interação 6 — Correção de erro

**Data:** 2 de novembro de 2026.

**Problema:** ao executar `php -l public/index.php`, foi apresentado o erro:

```text
PHP Fatal error: strict_types declaration must be the very first statement in the script
```

**Resposta:** a ferramenta explicou que `declare(strict_types=1)` precisa ser a primeira instrução do arquivo PHP.

**Decisão:** a organização inicial do arquivo foi corrigida, e a verificação de sintaxe passou a funcionar.

## Interação 7 — Documentação

**Data:** 2 de novembro de 2026.

**Solicitação:** atualizar o README, a modelagem, os testes e o diário de uso de LLM de acordo com as funcionalidades implementadas.

**Resposta:** revisão da documentação existente e elaboração de novos textos baseados no código disponível no repositório.

**Decisão:** os documentos foram preparados para revisão e incorporação ao projeto.

## Limitações e erros identificados

Durante o desenvolvimento, algumas respostas da ferramenta precisaram ser verificadas ou corrigidas.

Um exemplo ocorreu na documentação de testes, quando uma mensagem do terminal foi reproduzida com uma alteração de texto.

Outro problema ocorreu durante a substituição do código PHP, quando a posição da declaração `strict_types` provocou um erro de sintaxe.

Essas situações demonstraram a necessidade de conferir o código gerado, executar os testes e comparar a documentação com o comportamento real do sistema.

## Considerações finais

A LLM contribuiu para o desenvolvimento do projeto, mas não substituiu a execução dos testes nem a análise das funcionalidades.

O uso da ferramenta foi direcionado ao aprendizado, à implementação e à revisão do trabalho.
