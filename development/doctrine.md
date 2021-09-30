# Doctrine

- Definição de uma tabela/model
```sh
docker-compose exec app composer doctrine orm:mapping:describe Usuario
```

## Migrations

- Help
```sh
docker-compose exec app composer doctrine:migrations
```

- Dump do mapeamento para o banco
```sh
docker-compose exec app composer doctrine orm:schema-tool:create -- --dump-sql
```

- Status
```sh
docker-compose exec app composer doctrine:migrations migrations:status
```
```sh
docker-compose exec app composer doctrine orm:info
```

- Generate
```sh
docker-compose exec app composer doctrine:migrations migrations:generate
```

> ou ``docker-compose exec app composer doctrine:migrations migrations:diff``

- Migrate
```sh
docker-compose exec app composer doctrine:migrations migrate
```

- Rollback
```sh
docker-compose exec app composer doctrine:migrations migrations:migrate prev
```
