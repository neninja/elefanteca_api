# Documentação de suporte para o desenvolvimento

Instruções e comandos usuais ou não durante o desenvolvimento.

## Docker

- Caso modifique Dockerfile, rebuilde com ``docker-compose up -d --build``

### Mudar `DB_USERNAME` e `DB_PASSWORD`

Precisa remover a imagem (`docker ps -a` e `docker rm <id>`) e subir novamente (perdendo os dados anteriores)

## Doctrine

### Migrations

- Criação de migration em branco:
```sh
docker-compose exec app composer doctrine:migrations migrations:generate
```

- Criação de migration com comparação:
```sh
docker-compose exec app composer doctrine:migrations migrations:diff
```

> Lembrar de remover criação de tabelas existentes e schema public

- Criação/atualização:
```sh
docker-compose exec app composer doctrine:migrations migrate
```

- Rollback:
```sh
docker-compose exec app composer doctrine:migrations migrations:migrate prev
```
```txt
# psql está com problemas qnt isso:
# https://github.com/doctrine/migrations/issues/494
# https://github.com/doctrine/dbal/issues/1188
# https://github.com/doctrine/dbal/issues/1110
# solução: deletar manualmente as versões que não quer mais e gerar novamente com:
# 1) drop database:
#   composer doctrine orm:schema-tool:drop -- --force
# 2) Apagar arquivo da versão
# 3) Apagar histórico da versão:
#   delete from doctrine_migration_versions where version = 'App\Migrations\Doctrine\Version20210305010330';
```

- Descrição de entidade:
```sh
composer doctrine orm:mapping:describe Livro
```

- Status:
```sh
docker-compose exec app composer doctrine:migrations migrations:status
```

## Testes

- Simples
```sh
docker-compose exec app composer test tests/caminho/do/ExemploTest.php
```
> Um método de um arquivo: ``docker-compose exec app composer test -- --filter testName$ tests/caminho/do/ExemploTest.php``

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
> Dashboard de relatório de cobertura disponível em `tests/_reports/index.html`

- Testes de mutação
```sh
docker-compose exec app composer ci:mutation
```

> Veja o resultado no arquivo gerado em `docs/infection.diff`

### Configurações

- Atualizar `$tables` em `IntegrationTestCase`
- Atualizar `doctrineGetMetadatas` na trait `Doctrine`
