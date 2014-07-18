ZF2 DB Testing
==============

This is a tool for testing the DB components of [Zend Framework 2](https://github.com/zendframework/zf2) (ZF2) using a real database and some functional tests.

Installation
------------

To use this tool you need to install the ZF2 library that you want to test using [composer](https://getcomposer.org/).
You need to edit the `composer.json` file anche change the repository or packages according to your need.
For instance, if you want to check a specific ZF2 branch version stored in your repository you can use a configuration like that:

```js
{
    "repositories": [
    {
        "type"   : "package",
        "package": {
            "name"   : "zendframework/zf2",
            "version": "dev-master",
            "source" : {
                "url"      : "https://github.com/ezimuel/zf2.git",
                "type"     : "git",
                "reference": "origin/fix/#6445"
            }
        }
    },
...
```

In this example we are going to use the github repository `https://github.com/ezimuel/zf2.git` with the branch `fix/#6445`.
The composer.json will install this specific version of ZF2 in the vendor folder.

How to start
------------

Before to start executing test on the database you need to create it. To create a new database you have to edit the credentials for the connection.
The connection parameters are store in the `config/config.php` file. You need to create this file from the `config.php.dist` file.
If you are insinde the root folder of the project you can create from the dist file:

```bash
cp config/config.php.dist config/config.php
```

After the copy you need to edit the file adding the connection parameters of the database that you want to test.
For instance, if you want to test a [SQLite](http://www.sqlite.org/) database you need to specify only two parameters:

```php
<?php
return array(
    'driver'   => 'Pdo_Sqlite',
    'database' => sys_get_temp_dir() . '/test.sqlite' 
);
```

Create the database
-------------------

To create the database for testing you need to execute the following command, from the root folder of the project:

```bash
php bin/dbtest.php init
```

This command will create the test database.


Executing the test cases
------------------------

The tests are generated using simple PHP files stored in the `tests` folder, using the **test_** prefix.
An example of a SQL SELECT test can be something like that:

```php
$test   = 'LIMIT';
$select = $this->sql->select()->from(self::DBTESTNAME)->where(array('id' => 1));
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row    = $result->current();

echo $this->testMe($row['id'] == 1, $test);
```

You can see that the test contains some $this references to a `Zend\Db\Sql\Sql` object.
Moreover, we used a `testMe()` function that returns a successful string or not, depending on the condition.

You can create your own specific tests in new files stored in the `tests` folder.

To execute all the test you can use the following command, from the root folder of the project:

```bash
php bin/dbtest.php test
```

Delete the database
-------------------

You can delete the test database using the following command, from the root folder of the project:

```bash
php bin/dbtest.php clean
```

Author
------

This project has been developed by [Enrico Zimuel](http://www.zimuel.it) to create a functional test environment for the `Zend\Db` component of Zend Framework 2.


