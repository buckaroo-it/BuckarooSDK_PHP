<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/includes/App.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NullHandler;
use Buckaroo\Example\App;

$logger = new Logger('buckaroo-sdk');
if (!empty($_ENV['BPE_DEBUG'])) {
    $logger->pushHandler(new StreamHandler('logs/push.txt', Logger::DEBUG));
} else {
    $logger->pushHandler(new NullHandler());
}

$app = new App($logger);
$app->handlePush($_POST, $_ENV['BPE_SECRET_KEY']);