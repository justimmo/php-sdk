JUSTIMMO PHP-SDK
================
[![CI](https://github.com/justimmo/php-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/justimmo/php-sdk/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/github/tag/justimmo/php-sdk.svg)](https://github.com/justimmo/php-sdk/releases)
[![License](https://poser.pugx.org/justimmo/php-sdk/license.svg)](https://packagist.org/packages/justimmo/php-sdk)
[![Total Downloads](https://poser.pugx.org/justimmo/php-sdk/downloads.svg)](https://packagist.org/packages/justimmo/php-sdk)

Installation
------------
```bash
$ composer require justimmo/php-sdk "^2.0"
```

Documentation
-------------
<a href="https://api-docs.justimmo.at/php-sdk/index.html" target="_blank">Read the full documentation</a>

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
