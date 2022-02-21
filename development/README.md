# Desenvolvimento

Diretório com arquivos "documentando" o desenvolvimento, seja para auxiliar ou registrar para estudo.

## Arquitetura

### FAQ

#### Quando usar nome no plural/singular e português/inglês?

- Repository: **plural** e **português**
> `UsuariosRepository`

- Controller: **singular** e **inglês**
> `AuthorController`

- Endpoint: **plural** e **inglês**
> `AuthorsAPITestP  testa `/api/authors`

- Requisição e resposta JSON: **inglês**
> No banco está `nome` e na model `nome`, porém a resposta ou requisição será `name`

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
<!--
