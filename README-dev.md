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

### Instruções

- Nomear teste com verbo no imperativo explicitando a intencionalidade do teste, evitar nomes como "deve fazer x". [Orientação do time do Spotify](https://github.com/spotify/should-up)
- Quando for criada uma trait precisa ser recriado o autoload com ``composer du``

## Arquitetura

### FAQ

#### O que são classes *Provider*, *Repository* e *Service*?

A separação é feita para deixar explícita suas responsabilidades, sendo:

- ***Provider***: Adaptador de uma biblioteca
- ***Repository***: Adaptador de acesso ao banco de dados ou APIs
- ***Service***: Caso de uso específico com as regras de negócio. Não substitui a necessidade de haver lógica nas classes ***Models***

#### Por que em classes de *Provider* e *Repository* são usadas interfaces?

Para fazer a aplicação conhecer abstrações de algo que deve ser implementado, como por exemplo criptografar um texto ou persistir uma ***Model***. Como isso é feito, com qual banco ou biblioteca? Deve ser indiferente à aplicação

#### O que testar nos diferentes tipos de teste? (*unit*, *integration* e *e2e*)

- ***Unit***: Testes simples de ***Models*** que não dependam de Repositories e Providers
- ***Integration***: Testes mais avançados de ***Services*** com validações se as ***Repositories*** e ***Providers*** integraram como o esperado.
- ***E2E***: Testes dos *endpoints*, validando o comportamento básico e crítico esperado, comumente para testes de autorização.
