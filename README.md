# elefanteca_api

[![CI](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml/badge.svg)](https://github.com/nenitf/elefanteca_api/actions/workflows/ci.yml) [![emojicom](https://img.shields.io/badge/emojicom-%F0%9F%90%9B%20%F0%9F%86%95%20%F0%9F%92%AF%20%F0%9F%91%AE%20%F0%9F%86%98%20%F0%9F%92%A4-%23fff)](http://neni.dev/emojicom)

API de sistema para bibliotecas.

## Documentação online

- [Documentação de requisitos](https://neni.dev/elefanteca_api) ([simplificação](https://neni.dev/elefanteca_api/README.txt))
- [Relatório de testes](https://codepen.io/nenitf/full/GREQZRd?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/phpunit-log.xml)
- [Relatório de cobertura de testes](https://neni.dev/elefanteca_api/coverage/dashboard.html) ([simplificação](https://neni.dev/elefanteca_api/coverage.txt))
- [Referência da API](https://neni.dev/elefanteca_api/swagger/index.html?url=https://neni.dev/elefanteca_api/swagger/openapi.yaml) (demo não funcional)

## <a name="status"></a> Situação do projeto [:clipboard:](#status)

- [Tarefas](https://github.com/nenitf/elefanteca_api/issues)
- [Marcos](https://github.com/nenitf/elefanteca_api/milestones)
- [Planejamento](https://github.com/nenitf/elefanteca_api/projects/2)

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

6. Crie as tabelas no banco
```sh
docker-compose exec app composer doctrine:migrations migrate
```

7. Crie a documentação de suporte que ficará disponível em `localhost:8989/swagger`
```sh
docker-compose exec app composer swagger
```

## Execução local

Caso recém tenha feito a **configuração inicial** e os containers continuem rodando: tudo certo, aplicação disponível em ``localhost:8989``. Do contrário, suba os containers novamente:
```sh
docker-compose up -d
```

## Teste

- Individual
```sh
docker-compose exec app composer test tests/caminho/do/ExemploTest.php
```

- Completo
```sh
docker-compose exec app composer ci
```
