# Testes

- Simples
    ```sh
    docker-compose exec app composer test tests/caminho/do/ExemploTest.php
    ```
    > Um método de um arquivo: ``docker-compose exec app composer test -- --filter testName$ tests/caminho/do/ExemploTest.php``

- TDD
    ```sh
    ./tdd
    ```

    > O script executa o teste simples anterior, aceitando argumentos como ``./tdd -- --filter testName$ tests/caminho/do/ExemploTest.php``. Sua diferença é que executa novamente com qualquer tecla

- CI
    ```sh
    docker-compose exec app composer ci
    ```
    > Dashboard de relatório de cobertura disponível em `_reports/coverage/index.html`

- Testes de mutação
    ```sh
    docker-compose exec app composer ci:mutation
    ```

    > Veja o resultado no arquivo gerado em `_reports/infection.diff`

## Instruções

- Nomear teste com verbo no imperativo explicitando a intencionalidade do teste, evitar nomes como "deve fazer x". [Orientação do time do Spotify](https://github.com/spotify/should-up)
- Quando for criada uma trait precisa ser recriado o autoload com ``composer du``

### Testes e2e

- Para executar selects em testes e2e utilize ``DB::select('select * from usuarios');``
- Para analisar o retorno de uma requisição adicione `->dump()` após a `->response`, exemplo:
    ```diff
    <?php
        $this
            ->json('GET', self::$ep)
            ->response
    +       ->dump()
            ->assertOk();
    ```

### Github Actions

- [Visualizador JUnit (relatório de testes)](https://codepen.io/nenitf/full/GREQZRd?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/phpunit-log.xml)
- [Visualizador Clover (relatório de cobertura)](https://codepen.io/nenitf/full/NWwYQoz?url=https://raw.githubusercontent.com/nenitf/elefanteca_api/gh-pages/clover.xml)
- [Dashboard de cobertura](https://neni.dev/elefanteca_api/coverage/dashboard.html)
- [Relatório de cobertura simplificado](https://neni.dev/elefanteca_api/coverage.txt)
