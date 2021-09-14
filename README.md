# elefanteca_api

[![Tests](https://github.com/nenitf/elefanteca_api/actions/workflows/tests.yml/badge.svg)](https://github.com/nenitf/elefanteca_api/actions/workflows/tests.yml)

API de sistema para bibliotecas.

## Configuração inicial

1. Duplique `.env.example` e renomeie para `.env`
```sh
cp .env.example .env
```

2. **Mude o usuário (`DB_USERNAME`) e senha (`DB_PASSWORD`) de `.env`**

3. Crie os containers
```sh
docker-compose up -d
```
> Caso queira, ao final da configuração, pare os containers com ``docker-compose down``

4. Baixe as dependências do composer
```sh
docker-compose exec app composer install
```

5. Crie a chave de criptografia
```sh
docker-compose exec app php artisan key:generate
```

<!--
6. Crie as tabelas no banco
```sh
docker-compose exec app php artisan migrate --seed
```
> Limpar as tabelas e atualizar banco de acordo com as migrations com ``docker-compose exec app php artisan migrate:refresh --seed``

> Caso queira popular dados falsos para testar a aplicação manualmente, use ``docker-compose exec app php artisan db:seed --class FakeSeeder``

7. Crie a documentação de suporte (ficará disponível em `localhost:8989/public/swagger`)
```sh
docker-compose exec app composer swagger
```
-->

## Execução local

- Caso recém tenha feito a **configuração inicial** e os containers continuem rodando, tudo certo. Pode acessar ``localhost:8989``
- Do contrário, suba os containers novamente:
```sh
docker-compose up
```
> Pare com <kbd>Ctrl</kbd><kbd>C</kbd>

> Caso modifique Dockerfile, rebuilde com ``docker-compose up --build``

## Teste

- TDD
```sh
docker-compose exec app bash
```
```sh
composer test tests/caminho/do/ExemploTest.php
```
> Um método de um arquivo: ``composer test -- --filter testName$ tests/caminho/do/ExemploTest.php``

- CI
```sh
docker-compose exec app composer ci
```
> Testes que contenham `@group db` resetarão as migrations, portanto caso queira fazer testes manuais após o phpunit utilize ``docker-compose exec app php artisan migrate --seed``

> Também é gerada a "documentação" dos requisitos em [docs](docs)

> Coverage disponível em `tests/_reports/index.html`
