# Instalação

Faça um git clone do projeto template-php:
```
git@github.com:viniciusmattosrj/template-php.git
```

## Encoding

Todos os arquivos estão em **UTF-8**.


#### Importante para que o git não considere alterações de permissão como modificações a serem rastreadas

```
git config core.fileMode false
```

#### Utilizando o docker

No projeto **template-php** execute o comando abaixo:
```
composer install -vvv
```

Caso você utilize docker ao invés do NVM será necessário realizar a cópia do arquivo example:
```
cp -v docker-compose.yml.example docker-compose.yml
cp -v .env.example .env
```

Dentro do projeto **template-php** execute:
```
docker-compose up -d
```

#### Somente em ambiente de dev deve ser concedido a permissão nas pastas:

```
sudo chmod 777 -R vendor
```