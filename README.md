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
tdb

Usage Example
-------------
``` php
<?php

use Justimmo\Api\JustimmoApi;
use Psr\Log\NullLogger;
use Justimmo\Model\ObjektQuery;
use Justimmo\Cache\NullCache;
use Justimmo\Model\Wrapper\V1\ObjektListWrapper;
use Justimmo\Model\Wrapper\V1\ObjektWrapper;

$api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache());
$query = new ObjektQuery($api, new ObjektListWrapper(), new ObjektWrapper());
$objekte = $query->filterByPreis(array('min' => 500, 'max' => 1500))
    ->filterByPlz(1020)
    ->find();

foreach ($objekte as $objekt) {
    echo $objekt->getTitel() . ' ' . $objekt->getObjektnummer();
    //....
}

//fetching Objekt by PrimaryKey
$query->findPk(12345);
```
