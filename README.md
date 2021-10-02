# elefanteca_api

[![CI](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml/badge.svg)](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml) [![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](https://gist.github.com/nenitf/1cf5182bff009974bf436f978eea1996#emojicom)

API de sistema para bibliotecas.

## Configuração inicial

1. Duplique `.env.example` e renomeie para `.env`
```sh
cp .env.example .env
```

2. **Mude o usuário (`DB_USERNAME`) e senha (`DB_PASSWORD`) de `.env`**
> Caso não mude e utilize o valor de exemplo, para mudar depois terá que remover a imagem (`docker ps -a` e `docker rm <id>`) e subir novamente (perdendo os dados anteriores)

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

6. Crie as tabelas no banco
```sh
docker-compose exec app composer doctrine:migrations migrate
```
> Rollback com ``docker-compose exec app composer doctrine:migrations migrations:migrate prev``

<!--
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

- Teste simples
```sh
docker-compose exec app bash
```
```sh
composer test tests/caminho/do/ExemploTest.php
```
> Um método de um arquivo: ``composer test -- --filter testName$ tests/caminho/do/ExemploTest.php``

- TDD
```sh
docker-compose exec app bash
```
```sh
composer tdd
```
> Um arquivo: ``composer tdd tests/caminho/do/ExemploTest.php`` ou interativamente com <kbd>p</kbd><kbd>enter</kbd> ``tests/caminho/do/ExemploTest.php``

> Um método de um arquivo: ``composer tdd -- --filter testName$ tests/caminho/do/ExemploTest.php`` ou interativamente com <kbd>t</kbd><kbd>enter</kbd> ``testName$ tests/caminho/do/ExemploTest.php``

> Repetir último teste: <kbd>enter</kbd>

- CI
```sh
docker-compose exec app composer ci
```
> Também é gerada a "documentação" dos requisitos em `docs` (não versionada), a última versão é publicada pelo [Github Pages](https://neni.dev/elefanteca_api/) junto com [relatório de cobertura](https://neni.dev/elefanteca_api/coverage.txt). Caso algum teste falhe na branch **main** do repositório, é possível ver o erro detalhado por [aqui](https://codepen.io/nenitf/full/GREQZRd?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/phpunit-log.xml)

> Dashboard de relatório de cobertura disponível em `tests/_reports/index.html`
