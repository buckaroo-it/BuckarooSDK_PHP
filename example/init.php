<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/config.php');
require(__DIR__ . '/App.php');

use Buckaroo\SDK\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Buckaroo\SDK\Example\App;

$logger = new Logger('buckaroo-sdk');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

$client = new Client($logger);
$client->setWebsiteKey($websiteKey);
$client->setSecretKey($secretKey);
$client->setMode(Client::MODE_TEST);

$app = new App($logger);