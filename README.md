# elefanteca_api

[![CI](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml/badge.svg)](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml) [![coverage](https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/coverage.svg)](https://neni.dev/elefanteca_api/coverage/index.html) [![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](http://neni.dev/emojicom)

[![Swagger](https://validator.swagger.io/validator?url=https://neni.dev/elefanteca_api/swagger/openapi.yaml)](https://neni.dev/elefanteca_api/swagger/index.html?url=https://neni.dev/elefanteca_api/swagger/openapi.yaml) 

API de sistema para bibliotecas.

## <a name="status"></a> Situação do projeto [:clipboard:](#status)

- [Tarefas](https://github.com/nenitf/elefanteca_api/issues)
- [Marcos](https://github.com/nenitf/elefanteca_api/milestones)
- [Planejamento](https://github.com/nenitf/elefanteca_api/projects/2)

## Execução local com Docker

### Configuração inicial

1. Duplique `.env.example` e renomeie para `.env`
    ```sh
    cp .env.example .env
    ```

2. **Mude o usuário (`DB_USERNAME`), senha (`DB_PASSWORD`) e JWT (`JWT_KEY`) de `.env`**

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

7. Com o comando para resetar o banco, crie alguns dados básicos para a aplicação ser funcional em um primeiro momento, como por exemplo um usuário admin com email e senha ``admin@desativemeemprod.com`` ``asdf``
    ```sh
    docker-compose exec app php artisan db:reset
    ```

    > Para melhorar o ambiente de desenvolvimento com exemplos, utilize ``docker-compose exec app php artisan db:reset --development``


8. Crie a documentação de suporte que ficará disponível em `localhost:8989/swagger`
    ```sh
    docker-compose exec app composer swagger
    ```

### Execução

Com a **configuração inicial** já realizada, suba os containers se necessário e acesse a aplicação em `localhost:8989`

```sh
docker-compose up -d
```

### Teste

- Individual
    ```sh
    docker-compose exec app composer test tests/caminho/do/ExemploTest.php
    ```

- Completo
    ```sh
    docker-compose exec app composer ci
    ```
