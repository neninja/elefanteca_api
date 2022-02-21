# Testes

- Simples
```sh
docker-compose exec app composer test tests/caminho/do/ExemploTest.php
```
> Um método de um arquivo: ``docker-compose exec app composer test -- --filter testName$ tests/caminho/do/ExemploTest.php``

- TDD
```sh
bash tdd.sh
```

> O script executa o teste simples anterior, aceitando argumentos como ``bash tdd.sh -- --filter testName$ tests/caminho/do/ExemploTest.php``

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

## Configurações

- Atualizar `$tables` em `IntegrationTestCase`
- Atualizar `doctrineGetMetadatas` na trait `Doctrine`

## Instruções

- Nomear teste com verbo no imperativo explicitando a intencionalidade do teste, evitar nomes como "deve fazer x". [Orientação do time do Spotify](https://github.com/spotify/should-up)
- Quando for criada uma trait precisa ser recriado o autoload com ``composer du``
- Analise dos testes executados no github actions:
    - [Visualizador JUnit (relatório de testes)](https://codepen.io/nenitf/full/GREQZRd?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/phpunit-log.xml)
    - [Visualizador Clover (relatório de cobertura)](https://codepen.io/nenitf/full/NWwYQoz?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/clover.xml)
    - [Dashboard de cobertura](https://neni.dev/elefanteca_api/coverage/dashboard.html)
    - [Relatório de cobertura simplificado](https://neni.dev/elefanteca_api/coverage.txt)
