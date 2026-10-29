JUSTIMMO PHP-SDK
================
[![CI](https://github.com/justimmo/php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/justimmo/php-sdk/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/github/tag/justimmo/php-sdk.svg)](https://github.com/justimmo/php-sdk/releases)
[![License](https://poser.pugx.org/justimmo/php-sdk/license.svg)](https://packagist.org/packages/justimmo/php-sdk)
[![Total Downloads](https://poser.pugx.org/justimmo/php-sdk/downloads.svg)](https://packagist.org/packages/justimmo/php-sdk)

Installation
------------
#### Composer (recommended)

```
$ composer require justimmo/php-sdk "~1.2"
```
The wide constraint is on purpose: Composer installs the newest version your PHP can run. 1.4 and
later need PHP 8.1, so an older PHP gets an older 1.x instead of a failed install.

Composer generates a vendor/autoload.php file. You can simply include this file
```php  
require_once __DIR__.'/vendor/autoload.php';
```

If composer is not available you can set it up
```
$ curl -s https://getcomposer.org/installer | php
```
For more install options please refer to the <a href="https://getcomposer.org/download/" target="_blank">Composer Documentation</a>

#### manually
 * Download the latest stable release of php-sdk
 * Download https://github.com/php-fig/log
 * Extract php-sdk into your projects vendor folder
 * Extract log into the src folder of php-sdk

```php
require_once 'path_to_extraction/src/autoload.php';
```

Documentation
-------------
<a href="http://justimmo.github.io/php-sdk/index.html" target="_blank">Read the full documentation</a>

Usage Example
-------------
``` php
<?php

use Justimmo\Api\JustimmoApi;
use Psr\Log\NullLogger;
use Justimmo\Model\RealtyQuery;
use Justimmo\Cache\NullCache;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;

$api = new JustimmoApi('username', 'password');
$mapper = new RealtyMapper();
$wrapper = new RealtyWrapper($mapper);
$query = new RealtyQuery($api, $wrapper, $mapper);
$realties = $query->filterByPrice(['min' => 500, 'max' => 1500])
    ->filterByZipCode('1020')
    ->orderBy('price', 'desc')
    ->find();

foreach ($realties as $realty) {
    echo $realty->getTitle() . ' ' . $realty->getPropertyNumber();
    //....
}

//fetching Realty by PrimaryKey
$query->findPk(12345);
```

Strict mode
-----------
The api ignores a filter it does not have, or a value it cannot take, and answers with an
unfiltered list. A typo then looks like a working search. Since 1.5.0, strict mode rejects such a
request with an exception instead of sending it. It is off by default:

``` php
$api->setStrict(true);     // every query built on this api
$query->strict();          // or one query

$query->filterByPrice(1000);                           // throws: the price filter only takes a range
$query->filterByPrice(['min' => 1000, 'max' => 1000]); // fine
```

In 2.0.0, strict mode is always on. What strict mode rejects in 1.5.0, 2.0.0 will reject too, so
switching it on now shows what to change before the upgrade.
