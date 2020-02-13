#!/usr/bin/env php

<?php
if (php_sapi_name() !== 'cli') {
    exit;
}
require __DIR__ . '/vendor/autoload.php';

use App\Command\ConverterController;

$app = new Lib\App();
$app->registerController('convert', new ConverterController($app));
$app->registerCommand('help', function (array $argv) use ($app) {
    $app->getPrinter()->display("usage: convert [ limit ] [ date ]");
});
$app->runCommand($argv);
