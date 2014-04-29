# Query
Queries are a objectoriented way to fetch data from the api and abstract the results. They use a mapper and a wrapper to transform the result into a abstracted object. If you want to update the api to another version you just have to inject another mapper and wrapper into the query.
``` php
<?php
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;

$mapper = new RealtyMapper();
$wrapper = new RealtyWrapper($mapper);
$query = new RealtyQuery($api, $wrapper, $mapper);
$realties = $query->filterByPrice(array('min' => 500, 'max' => 1500))
    ->filterByZipCode(1020)
    ->orderBy('price', 'desc')
    ->find();
```

## Default Methods
The query instance has an internal parameter array with key/value pairs which will be translated into a query string when sent to the api by a termination method. You can combine as many methods as you like before calling a termination method.

#### ->set($key, $value)
Adds a parameter to the query what will be translated into the url on the call
``` php

//this would add &culture=en to the url of the call
$query->set('culture', 'en');
```
#### ->setLimit(), ->setOffset()
Sets the limit and the offset for the next call. They work like they would do in SQL.

#### ->clear()
Clears the query instance to it's state right after __construct()

#### ->orderBy($column, $direction = 'asc')
Adds the order in what the results should be returned on the next call

#### ->filter($key, $value)
They can be used to search the Justimmo data for specific values.
``` php

//filter by a spefic value
$query->filterByPrice(1500);

//filter by a range
$query->filterByPrice(array('min' => 500, 'max' => 1500));

//filter by multiple values
$query->filterByZipCode(array('1020', '1050'));
```
#### ->filterByXXX($value)
Shortcut for ->filter('XXX', $value);

#### ->find()
find is a termination method which passes all previously set parameters on the query to the api and returns a by the wrappers transformed result. Find returns a ListPager instance and clears the current query instance.

#### ->findOnde()
findOne is a termination method which passes all previously set parameters on the query to the api and returns a by the wrappers transformed result. Find returns a model instance depending on what query it is.

#### ->findPk($pk)
findPk is a termination method which only passes the primary key of a object to the api and returns a model instance

#### ->paginate($page = 1, $maxPerPage = 10)
find is a termination method which passes all previously set parameters on the query to the api and returns a by the wrappers transformed result. Find returns a ListPager instance and clears the current query instance. The ListPager instance only has the data of the page passed to pagina
They can be used to search the Justimmo data for specific values.
``` php

//example of a query which would return 100 results overall
$query->paginate(1, 10); //returns datarows 1 to 10
$query->paginate(3, 10); //returns datarows 21 to 30
$query->paginate(3, 15); //returns datarows 31 to 45
```

## RealtyQuery
The RealtyQuery additionally supports following methods. Keep in mind that you can add not integrated default parameters by using __->set()__ and __->filter()__
* filterByPrice($value)
* filterByRealtyTypeId($value)
* filterByRealtyCategory($value)
* filterByTag($value)
* filterByZipCode($value)
* filterByRooms($value)
* filterByPropertyNumber($value)
* filterByLivingArea($value)
* filterByFloorArea($value)
* filterBySurfaceArea($value)
* filterByKeyword($value)
* filterByFederalStateId($value)

## ProjectQuery
The ProjectQuery additionally supports following methods. Keep in mind that you can add not integrated default parameters by using __->set()__ and __->filter()__
* filterByRealtyCategory($value)
* filterByTag($value)
* filterByKeyword($value)
* filterByFederalStateId($value)
* all(1|0)

## BasicDataQuery
The BasicDataQuery differs from the other queries because its used to fetch Justimmo Basic Data like Countries, Federal States, ... It does not return abstracted objects but php arrays with the primary key as array key
``` php

use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Mapper\V1\BasicDataMapper();


$query = new BasicDataQuery($api, new BasicDataWrapper(), new BasicDataMapper());
$query->all(true)
    ->filterByCountry('AT')
    ->findFederalStates();
```

#### ->set()
See default methods

#### ->all(true|false)
Defines if the next call should return all results or only results associated with currently active realties. Example: Without __->all(true)__ a findCountries() calls would only return contries in which the Justimmo Client associated with the used api account would have active realties.

#### ->filterByCountry($value)
Filters the results by country. Accepts the Justimmo PK of the country or a ISO2 country code.

#### ->filterByFederalState($value)
Filters the results by federal state. Accepts the Justimmo PK of the federal state or the name of the federal state (currently german only).

#### ->findCountries()
Returns an array of countries
``` php

//return array format
array(
    pk => array(
        'name' => '...',
        'iso2' => '...',
        'iso3' => '...',
    ),
    //...
);
```

#### ->findFederalStates()
Returns an array of federal states
``` php

//return array format
array(
    pk => array(
        'name'      => '...',
        'countryId' => '...',
        'fipsCode'  => '...',
    ),
    //...
);
```

#### ->findZipCodes()
Returns an array of zip codes and their place name
``` php

//return array format
array(
    pk => array(
        'countryId'      => '...',
        'regionId'       => '...',
        'zipCode'        => '...',
        'place'          => '...',
        'federalStateId' => '...',
    ),
    //...
);
```

#### ->findRegions()
Returns an array of regions
``` php

//return array format
array(
    pk => 'name',
    //...
);
```

#### ->findRealtyTypes()
Returns an array of realty types
``` php

//return array format
array(
    pk => array(
        'name'      => '...',
        'key'       => '...',
        'attribute' => '...',
    ),
    //...
);
```
