JUSTIMMO PHP-SDK
=======
[![Build Status](https://travis-ci.org/justimmo/php-sdk.png)](https://travis-ci.org/justimmo/php-sdk)
[![Latest Stable Version](https://poser.pugx.org/justimmo/php-sdk/version.png)](https://packagist.org/packages/justimmo/php-sdk)

Installation
------------

composer.json

``` json
{
    "require": {
        "justimmo/php-sdk": "dev-master"
    }
}
```

Documentation
-------------
* [Basics](doc/basics.md)
* [Query](doc/query.md)
* [Caching](doc/caching.md)
* [Logging](doc/logging.md)
* [Expose](doc/expose.md)
* [RealtyInquiry](doc/inquiry.md)

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
