<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/config.php');
require(__DIR__ . '/App.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Buckaroo\SDK\Example\App;

$logger = new Logger('buckaroo-sdk');
$logger->pushHandler(new StreamHandler('logs/push.txt', Logger::DEBUG));

$app = new App($logger);
$app->handlePush($_POST, $secretKey);