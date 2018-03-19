<?php

$reflClass = new ReflectionClass('Justimmo\Api\Entity\Realty\SubRealtyType');
$reflId    = $reflClass->getProperty('id');
$reflName  = $reflClass->getProperty('name');

$reflId->setAccessible(true);
$reflName->setAccessible(true);

$data = [
    61  => 'Bürofläche',
    45  => 'Wohnung',
    54  => 'Terrassenwohnung',
    103 => 'Erdgeschoß',
    8   => 'Einfamilienhaus',
    19  => 'Bungalow',
    20  => 'Villa',
    15  => 'Reihenendhaus',
    9   => 'Mehrfamilienhaus',
    10  => 'Reihenhaus',
];

$fixtures = [];

foreach ($data as $id => $name) {
    $instance = $reflClass->newInstance();
    $reflId->setValue($instance, $id);
    $reflName->setValue($instance, $name);

    $fixtures[] = $instance;
}

return $fixtures;
