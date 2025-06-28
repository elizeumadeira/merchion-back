# Projeto Back Merchion

Projeto feito usando o servidor embutido no PHP 8.3.6

O projeto é feito com Laravel 12 é necessário rodar ``composer install`` para montar o projeto ja com as dependencias. Após completar a instalação, rodar o comando para adicionar as tabelas

```bash
  php artisan migrate
```

O projeto possui apenas a API do projeto. As rotas disponíveis podem ser visualizadas através do comando 

```bash
  php artisan route:list
```

Antes de dar um start na API, é necessário adicionar o endereço local no arquivo de hosts. Estou disponibilizando para os dois sistemas, front e back:

```bash
	127.0.0.1       elizeu-back-teste.test
	127.0.0.1       elizeu-front-teste.test
```

Após esses passso, basta rodar o projeto usando o comando abaixo. Estou setando com uma porta personalizada:

```bash
php artisan serve --host=elizeu-back-teste.test --port=8080
```

# Considerações

Tentei deixar o projeto mais enxuto possível por se tratar de uma API. Todas as configurações de JS e mais algumas pastas.

O projeto de back é muito simples. Não deu pra adicionar classes de interface nem exemplificar situações onde os princípios SOLID foram aplicados.

## Arquivos criados/alterados

Para facilitar a análise, vou adicionar uma lista dos arquivos criados/alterados para o projeto de back:

- bootstrap\app.php
- routes\api.php
- app\Http\Middleware\IsLogged.php
- config\cors.php
- app\Http\Controllers\UserController.php
- app\Models\User.php

Tentei adicionar comentários onde possível mas acredito que todas as alterações são fáceis de entender.

Adicionei a collection e environment do POSTMAN para o projeto na pasta postman