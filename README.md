Pingdom REST API
================

A PHP module to make use of the [Pingdom REST API](http://www.pingdom.com/services/api-documentation-rest/) for you to automate your interaction with the Pingdom system.

Installation
============

The best way to install the library is by using [Composer](http://getcomposer.org). Add the following to `composer.json` in the root of your project:

``` javascript
{ 
    "require": {
        "sgrodzicki/pingdom": "1.0.*"
    }
}
```

Then, on the command line:

``` bash
curl -s http://getcomposer.org/installer | php
php composer.phar install
```

Use the generated `vendor/.composer/autoload.php` file to autoload the library classes.

Basic usage
===================

```php
<?php

$username = ''; // Pingdom username
$password = ''; // Pingdom password
$token    = ''; // Pingdom application key (32 characters)

$pingdom = new \Pingdom\Client($username, $password, $token);
$pingdom->getChecks();
```

Tests
=====

[![Build Status](https://secure.travis-ci.org/sgrodzicki/pingdom.png?branch=master)](http://travis-ci.org/sgrodzicki/pingdom)

The client is tested with phpunit; you can run the tests, from the repository's root, by doing:

``` bash
phpunit
```

Some tests require internet connection (to test against a real API response), so they are disabled by default; to run them add a `credentials.php` file in the root of your project:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$username = '[your username]';
$password = '[your password]';
$token    = '[your token]';
```

and run the tests, from the repository's root, by doing:

``` bash
phpunit --bootstrap credentials.php
```