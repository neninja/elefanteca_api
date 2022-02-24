# Docker

- Caso modifique Dockerfile, rebuilde com ``docker-compose up -d --build``

## Mudar `DB_USERNAME` e `DB_PASSWORD`

Precisa remover a imagem (`docker ps -a` e `docker rm <id>`) e subir novamente (perdendo os dados anteriores)
