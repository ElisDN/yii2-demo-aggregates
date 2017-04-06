Yii 2 DDD Aggregates Demo
============================

Demonstration of domain entities and repositories.

INSTALLATION
------------

You can then clone this project template using the following command:

~~~
git clone git@github.com:ElisDN/yii2-demo-aggregates project
cd project
composer install
~~~

CONFIGURATION
-------------

Add the file `config/db.php` with real data, for example:

```php
<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=aggregates',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

Add the file `config/test_db.php` for test data, for example:

```php
<?php
$db = require(__DIR__ . '/db.php');
$db['dsn'] = 'mysql:host=localhost;dbname=aggregates_test';
return $db;
```

Apply migrations:

```
php yii migrate
php tests/bin/yii migrate
```

TESTING
-------

Tests can be executed by running:

```
vendor/bin/codecept run unit entities
vendor/bin/codecept run unit repositories
``` 
