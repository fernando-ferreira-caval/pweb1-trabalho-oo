# Diário de uso de LLM

Durante o desenvolvimento do projeto, utilizei o ChatGPT como ferramenta de apoio para compreender conceitos de programação orientada a objetos, desenvolver as classes, organizar o código e resolver problemas encontrados durante a execução.

As sugestões foram analisadas e testadas antes de serem incorporadas ao projeto.

## Interação 1

- **Ferramenta e data:** ChatGPT, outubro de 2026.
- **Prompt:**

```text
Estou desenvolvendo um sistema de Transporte Escolar para um trabalho de PHP com orientação a objetos. Preciso organizar as classes e aplicar os conceitos de herança, composição, encapsulamento e polimorfismo.
```

- **Resumo da resposta:** A ferramenta ajudou a estruturar o sistema, sugerindo as classes `Veiculo`, `Onibus`, `Van`, `Aluno` e `Rota`, além de explicar como os conceitos de orientação a objetos poderiam ser aplicados.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Utilizei a estrutura sugerida porque ela atendia aos requisitos do trabalho e representava as entidades principais do sistema.

## Interação 2

- **Ferramenta e data:** ChatGPT, outubro de 2026.
- **Prompt:**

```text
Preciso implementar as classes do sistema em PHP utilizando atributos privados, construtores, métodos e herança. Explique como organizar cada classe em seu próprio arquivo.
```

- **Resumo da resposta:** A ferramenta auxiliou na criação das classes, na definição dos atributos e métodos e na aplicação de herança entre `Veiculo`, `Onibus` e `Van`.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Aproveitei a estrutura apresentada e utilizei os testes no terminal para verificar se as classes funcionavam corretamente.

## Interação 3

- **Ferramenta e data:** ChatGPT, outubro de 2026.
- **Prompt:**

```text
Estou desenvolvendo a interface do sistema e preciso ajustar o CSS para organizar o cabeçalho, o menu, o conteúdo principal, os formulários e o rodapé.
```

- **Resumo da resposta:** A ferramenta sugeriu ajustes na organização do CSS, incluindo alinhamento dos elementos, centralização do conteúdo, espaçamento e apresentação dos formulários.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Utilizei os ajustes sugeridos e verifiquei o resultado no navegador, mantendo as alterações que melhoraram a apresentação da interface.

## Interação 4

- **Ferramenta e data:** ChatGPT, outubro e novembro de 2026.
- **Prompt:**

```text
Estou encontrando erros durante a execução do projeto PHP. Vou enviar os comandos e as mensagens do terminal para identificar o problema e corrigir o código.
```

- **Resumo da resposta:** A ferramenta auxiliou na identificação de problemas relacionados à execução do PHP, à organização dos arquivos e ao funcionamento das funcionalidades do sistema.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Analisei as orientações, apliquei as correções necessárias e executei novamente os comandos para verificar se os problemas haviam sido resolvidos.

## Interação 5

- **Ferramenta e data:** ChatGPT, novembro de 2026.
- **Prompt:**

```text
Preciso testar meu sistema e documentar os resultados. Vou enviar a saída do terminal e as respostas das requisições HTTP para organizar o arquivo testes.md.
```

- **Resumo da resposta:** A ferramenta ajudou a organizar os testes de polimorfismo, cadastro de alunos, validação das regras de negócio, páginas HTTP, respostas JSON e tratamento de erros do formulário.
- **O que aceitei, modifiquei ou rejeitei, e por quê:** Utilizei os resultados reais obtidos no terminal e no navegador para preencher a documentação, evitando registrar resultados que não haviam sido testados.

## O erro que a LLM cometeu

Durante a organização do arquivo `testes.md`, a ferramenta alterou incorretamente uma linha da saída original do terminal. A mensagem `Carlos adicionado com sucesso.` foi reproduzida como `Carlos adicionados com sucesso.`.

O erro foi identificado pela comparação com a saída real do terminal. A documentação deve preservar o resultado original, portanto a linha precisa ser corrigida.