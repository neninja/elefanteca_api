# elefanteca_api

API de sistema para bibliotecas

## Configuração inicial

1. Crie os containers
```sh
docker-compose up -d
```
> Caso queira, ao final da configuração, pare os containers com ``docker-compose down``

2. Baixe as dependências do composer
```sh
docker-compose exec app composer install
```

3. Crie a chave de criptografia
```sh
docker-compose exec app php artisan key:generate
```

<!--
3. Crie as tabelas no banco
```sh
docker-compose exec app php artisan migrate --seed
```
> Limpar as tabelas e atualizar banco de acordo com as migrations com ``docker-compose exec app php artisan migrate:refresh --seed``

> Caso queira popular dados falsos para testar a aplicação manualmente, use ``docker-compose exec app php artisan db:seed --class FakeSeeder``

4. Crie a documentação de suporte (ficará disponível em `localhost:8989/public/swagger`)
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

> Caso modifique Dockerfile, rebuilde com ``docker-compose up -d --build``

## Teste

- Para testes durante o desenvolvimento
```sh
docker-compose exec app bash
```
```sh
composer test
```
> Um arquivo: ``composer test tests/Ipath/to/CadastroTest.php``

> Um método de um arquivo: ``composer test -- --filter testName$ tests/Ipath/to/CadastroTest.php``

- Para ci 
```sh
docker-compose exec app composer ci
```
> Testes que contenham `@group db` resetarão as migrations, portanto caso queira fazer testes manuais após o phpunit utilize ``docker-compose exec app php artisan migrate --seed``

> Relatório: ``docker-compose exec app cat tests/_reports/tests.txt``

> Coverage disponível em `tests/_reports/index.html`
