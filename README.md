# elefanteca_api

API de sistema para bibliotecas

## Configuração inicial

1. Duplique `.env.example` e renomeie para `.env`
```sh
cp .env.example .env
```

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

4. Crie as tabelas no banco
```sh
docker-compose exec app php artisan migrate --seed
```
> Limpar as tabelas e atualizar banco de acordo com as migrations com ``docker-compose exec app php artisan migrate:refresh --seed``

> Caso queira popular dados falsos para testar a aplicação manualmente, use ``docker-compose exec app php artisan db:seed --class FakeSeeder``

5. Crie a documentação de suporte (ficará disponível em `localhost:8989/public/swagger`)
```sh
docker-compose exec app composer swagger
```

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
docker-compose exec app bash # acessa o container
```
```sh
composer test # executa testes dentro do container
```

- Para ci 
```sh
docker-compose exec app composer ci
```
> Testes que contenham `@group db` resetarão as migrations, portanto caso queira fazer testes manuais após o phpunit utilize ``docker-compose exec app php artisan migrate --seed``
