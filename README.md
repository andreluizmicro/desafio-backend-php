## Transfer microservice

### Instalação e execução da API

Para auxiliar na configuração do projeto temos um arquivoo `Makefile` com ele é possível executar todas as configurações necessárias para que a aplicação funcione.

para executar os comando abaixo basta digitar antes do comando:

Exemplo:

    make docker-up

Principais comandos:

- `docker-build`: Faz o build do projeto
- `docker-up`: Sobre os containers da aplicação
- `docker-down`: Para todos os containers da aplicação
- `docker-bash`: Acessa o bash do container da aplicação
- `docker-format`: Faz a formatação dos arquivos php
- `docker-test`: Executa todos os testes da aplicação
- `docker-test-coverage`: Executa os testes e gera um relatório de cobertura dos testes
