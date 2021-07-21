<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/App.php');

use Buckaroo\SDK\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$websiteKey = "PUT_YOUR_WEBSITE_KEY_HERE";
$secretKey = "PUT_YOUR_SECRET_KEY_HERE";

$orderId = 'sdk_' . date('ymdHis') . rand(1, 99);
$currencyCode = 'EUR';
$baseUrl = 'https://sdk.buckaroo.vlad.hysdev.com/example/';
$returnURL = $baseUrl . 'return.php';
$returnURLCancel = $baseUrl . 'return.php';
$pushURL =  $baseUrl . 'push.php';
$ip = '45.14.110.5';

$logger = new Logger('buckaroo-sdk');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

$client = new Client($logger);
$client->setWebsiteKey($websiteKey);
$client->setSecretKey($secretKey);
$client->setMode(Client::MODE_TEST);
