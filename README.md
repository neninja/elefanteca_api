# elefanteca_api

[![Tests](https://github.com/nenitf/elefanteca_api/actions/workflows/tests.yml/badge.svg)](https://github.com/nenitf/elefanteca_api/actions/workflows/tests.yml) [![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](https://gist.github.com/nenitf/1cf5182bff009974bf436f978eea1996#emojicom)

API de sistema para bibliotecas.

## Configuração inicial

1. **Mude o usuário (`DB_USERNAME`) e senha (`DB_PASSWORD`) e senha de [.env](.env)**

2. Crie os containers
```sh
docker-compose up -d
```
> Caso queira, ao final da configuração, pare os containers com ``docker-compose down``

3. Baixe as dependências do composer
```sh
docker-compose exec app composer install
```

4. Crie a chave de criptografia
```sh
docker-compose exec app php artisan key:generate
```

<!--
5. Crie as tabelas no banco
```sh
docker-compose exec app php artisan migrate --seed
```
> Limpar as tabelas e atualizar banco de acordo com as migrations com ``docker-compose exec app php artisan migrate:refresh --seed``

> Caso queira popular dados falsos para testar a aplicação manualmente, use ``docker-compose exec app php artisan db:seed --class FakeSeeder``

6. Crie a documentação de suporte (ficará disponível em `localhost:8989/public/swagger`)
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

> "Documentação" dos requisitos: ``docker-compose exec app cat tests/_reports/tests.txt``

> Coverage disponível em `tests/_reports/index.html`
