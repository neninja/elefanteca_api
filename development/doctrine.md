# Doctrine

- Definição de uma tabela/model
    ```sh
    docker-compose exec app composer doctrine orm:mapping:describe Usuario
    ```

## Migrations

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

- Executa migration:
    ```sh
    docker-compose exec app composer doctrine:migrations migrate
    ```

- Reset database:
    ```sh
    docker-compose exec app composer doctrine:migrations migrate first
    docker-compose exec app composer doctrine:migrations migrate latest
    ```

    > Comando abstraido para desenvolvimento com ``docker-compose exec app php artisan db:reset --development``
