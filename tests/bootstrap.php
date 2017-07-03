<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Justimmo\\Api\\Tests\\', __DIR__);

AnnotationRegistry::registerLoader('class_exists');
