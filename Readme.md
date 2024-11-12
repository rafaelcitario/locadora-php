# Sistema de Locadora em PHP Puro e MySQL

Este projeto é um sistema de locadora desenvolvido em PHP puro e MySQL. O objetivo principal é o aprofundamento no estudo do PHP, sem a utilização de frameworks. A intenção é construir uma base sólida de conhecimento em PHP, que servirá como alicerce para o aprendizado de frameworks como o Laravel futuramente.

## Funcionalidades

O sistema de locadora, em sua fase inicial, visa oferecer as seguintes funcionalidades:

- **Cadastro de clientes**: Permite registrar novos clientes com informações relevantes, como nome, endereço e contato.
- **Cadastro de filmes**: Possibilita adicionar filmes ao catálogo da locadora, incluindo título, gênero, diretor e outras informações relevantes.
- **Locação de filmes**: Permite que clientes aluguem filmes disponíveis no catálogo, registrando a data de locação e devolução prevista.
- **Devolução de filmes**: Registra a devolução dos filmes, calcula o valor total da locação e gerencia eventuais multas por atraso.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação principal utilizada para desenvolver toda a lógica do sistema.
- **MySQL**: Sistema de gerenciamento de banco de dados relacional utilizado para armazenar as informações do sistema.

## Estrutura do Projeto

O projeto está organizado da seguinte forma:

- **`src/`**: Contém os arquivos de código-fonte do sistema, organizados em pastas de acordo com sua funcionalidade.
- **`bootstrap.php`**: Arquivo de inicialização do sistema, responsável por carregar as configurações e dependências.
- **`.env.example`**: Arquivo de exemplo para configuração de variáveis de ambiente, como as credenciais de acesso ao banco de dados.
- **`composer.json`**: Define as dependências do projeto gerenciadas pelo Composer.
- **`docker-compose.yaml`**: Arquivo de configuração para execução do projeto em ambiente Docker.

## Próximos Passos

- **Implementação completa das funcionalidades básicas**: Cadastro de clientes, filmes, locação e devolução.
- **Criação de uma interface de usuário**: Desenvolvimento de uma interface web para interação com o sistema.
- **Implementação de recursos avançados**: Buscar aprimorar o sistema com funcionalidades como sistema de busca, filtros, histórico de locações, etc.
- **Migração para Laravel**: Após consolidar o conhecimento em PHP puro, o projeto será migrado para o framework Laravel, visando explorar recursos avançados e otimizar o desenvolvimento.
