JUSTIMMO PHP-SDK
================
[![Build Status](https://api.travis-ci.org/justimmo/php-sdk.png)](https://travis-ci.org/justimmo/php-sdk)
[![Latest Stable Version](https://poser.pugx.org/justimmo/php-sdk/version.png)](https://packagist.org/packages/justimmo/php-sdk)
[![License](https://poser.pugx.org/justimmo/php-sdk/license.svg)](https://packagist.org/packages/justimmo/php-sdk)
[![Total Downloads](https://poser.pugx.org/justimmo/php-sdk/downloads.svg)](https://packagist.org/packages/justimmo/php-sdk)

Installation
------------
#### Composer (recommended)

```
$ composer require justimmo/php-sdk "1.0.*"
```
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

$api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
$mapper = new RealtyMapper();
$wrapper = new RealtyWrapper($mapper);
$query = new RealtyQuery($api, $wrapper, $mapper);
$realties = $query->filterByPrice(array('min' => 500, 'max' => 1500))
    ->filterByZipCode(1020)
    ->orderBy('price', 'desc')
    ->find();

foreach ($realties as $realty) {
    echo $realty->getTitle() . ' ' . $realty->getPropertyNumber();
    //....
}

//fetching Realty by PrimaryKey
$query->findPk(12345);
```
