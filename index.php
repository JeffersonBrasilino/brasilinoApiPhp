<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once __DIR__.'/vendor/autoload.php';


$app = new \core\App();
$app->setRoutesFile('pub/routes.json');
$app->start();