Basics
=======

Autoloading
-----------
The easiest way is to autoload the classes in you php application is using <a href="http://getcomposer.org/">Composer</a> to install the SDK.
The SDK follows the <a href="http://www.php-fig.org/psr/psr-0/">PSR-0</a> standards for autoloading.

The Api Class
-------------
The Api class handles the communication between the Justimmo API and the SDK. It has to be injected in every query.
Mandatory Requirements for the api class when instancing it is a Username and a Password for the Justimmo API, a LoggerInterface and a CacheInterface.
Optional Attributes are the version (default __v1__) and the culture (default __de__). The culture defines the default language in which the api returns values. You can override this per call if needed.
``` php
<?php

use Justimmo\Api\JustimmoApi;
use Psr\Log\NullLogger;
use Justimmo\Model\RealtyQuery;
use Justimmo\Cache\NullCache;

$api = new JustimmoApi('username', 'password', new NullLogger(), new NullCache(), 'v1', 'de');
//.....
$api->setCulture('en');
//.....
```

Exceptions
----------
The SDK throws a variety of Exceptions you can catch and handle into appropiate messages

Abstraction
-----------
The concept of this SDK is to abstract the JUSTIMMO Api to a point that you just have to change the mapper and wrapper of a Query to use a different version of the Api. To achieve this the api uses Dependency Injection. If you don't want to build you dependencies by hand when using the api, you can use a <a href="http://symfony.com/doc/current/components/dependency_injection/introduction.html" target="_blank">DependencyInjection Container</a>.

Queries
-------
Queries are a objectoriented way you can use to fetch data from the api. You can find a full list of possibilities in the Query-Documentation.
``` php
<?php
//...

$query = new RealtyQuery($api, $wrapper, $mapper);
$realties = $query->filterByPrice(array('min' => 500, 'max' => 1500))
    ->filterByZipCode(1020)
    ->orderBy('price', 'desc')
    ->find();
```

Model
-----
Model Classes are abstracted representations of a singular data set (example Realty, Employee,...)
Instances of model classes are created by wrappers which handle the translation of the output format of a api call (xml, json) into an abstracted model object. By default Queries return model instances while api calls return the plain outputformat as string.
Depending on the dataset they come with a variety of different getters. A full list of all properties you'll find in the model classes.
``` php
<?php
//...

$realty = $query->findPk(12345);
echo $realty->getPurchasePrice();
echo $realty->getTitle();
//...

//information about the contact person of that realty
echo $realty->getContact()->getFirstName();
echo $realty->getContact()->getEmail());

//...

//attachments getPictures() getVideos() getDocuments()
foreach($realty->getPictures() as $picture) {
    echo $picture->getUrl('orig');
}
//abstracted attachments
foreach($realty->getAttachmentsByType('bilder360') as $picture) {
    echo $picture->getUrl('orig');
}

```

Pagination
----------
Queries return by default a ListPager instance wich is a extension of a PHP Array Object and only holds a a subset of data of all data and additional methods to build a pager

``` php
<?php
//iterating trough the subset of data
foreach($pager as $realty) {
    //...
}

$pager->haveToPaginate(); //if the pager contains all data of if we have to paginate
$pager->getPage(); //the current page of the pager
$pager->getFirstPage(); //the first page of the pager
$pager->getMaxPerPage(); //how many entries are shown on a page
$pager->getLastPage(); //the last page of the pager
$pager->getNbResults(); //number of all found results not only the number contained on the current page
$pager->getLinks(5);  //gets the numbers of the adjacent pages of the current page if available

```