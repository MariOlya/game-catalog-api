<p align="center">
    <h1 align="center">Game Catalog API</h1>
    <h3 align="center">test task by Olga Marinina</h3>
</p>
<p align="center">
<img src="https://img.shields.io/badge/php-%5E8.2.0-blue">
<img src="https://img.shields.io/badge/mysql-~8.0.33-orange">
<img src="https://img.shields.io/badge/yii2-~2.0.45-green">
<img src="https://img.shields.io/badge/phpunit-~9.5.0-blue">

[//]: # (<img src="https://img.shields.io/badge/redis-5-red">)
</p>
<br>


Requirements
-------------------

Предлагаем выполнить следующее тестовое задание:
Сделать web api для взаимодействия с базой данных, в которой хранятся данные о видеоиграх.
Реализовать CRUD операции с ней, а также метод для получения списка игр определённого жанра.
Информация об игре: название, студия разработчик, несколько жанров, которым соответствует игра. Используя любой фреймворк или без фреймворка. Действуя согласно SOLID MVC MVVM.
Сделать минимум 3 слоя абстракций, а контроллеры "тонкими".
На выполнение тестового задания отводится неделя времени.
Выполненное тестовое задание можно передать любым удобным способом: ссылка на git, ссылка на диск или архив.



DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      docker/             contains data from DB/sphinx volumes
      fixtures/           contains fake data for DB
      mail/               contains view files for e-mails
      migrations/         contains migrations to create current tables for DB
      runtime/            contains files generated during runtime
      src/                contains classes (domain, infrustacture, application)
        application/      contains classes for factories, services of internal work
        domain/           contains models of main entities
        infrastructure/   contains helped models (forms), constants, jobs for queues and services of external work
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      widgets/            contains some widgets



REQUIREMENTS
------------

We work on this project with docker-compose.

**Images**:
* yiisoftware/yii2-php:8.2-apache
* mysql:8.0.33

To start project you need to add this command in the terminal

```
docker-compose up -d
```

You can then access the application locally through the following URL:

    http://127.0.0.1:8000



CONFIGURATION
-------------

### Database

File `config/db.php` with real data. For example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```


### Migrations

Migrations can be started via the command

```
docker-compose run php ./yii migrate
```

Migrations can be denied via the command

```
docker-compose run --rm php ./yii migrate/down
```


### Fake data aka fixtures

We have already generated data and add they `app/fixtures/data`.
You should run they sequentially via the command (for example, for ExampleFixture)

```
docker-compose run --rm php yii fixture/load Example
```

**The sequence**:
1. Studio
2. Genre
3. Game
4. GameGenre

If you want to generate your personal data then use our templates in `app/fixtures/templates` but you should keep *these rules*:
1. absolutely follow the sequence above
2. generate data one at a time
3. run the same fixture
4. take new table and generate data for this

The command to generate data

```
docker-compose run --rm php yii fixture/generate example --count=n
```

TESTING
-------

Tests are located in `tests` directory. We use only unit tests on this project.

Unit tests can be executed by running

```
vendor/bin/codecept run unit
```

Functional tests can be executed by running

```
vendor/bin/codecept run functional
```


### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
