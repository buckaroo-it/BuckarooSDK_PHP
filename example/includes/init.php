<?php
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../includes/App.php');

\Dotenv\Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..')->safeLoad();

$logger = new \Monolog\Logger('buckaroo-sdk');
if (!empty($_ENV['BPE_DEBUG'])) {
    if (php_sapi_name() == 'cli') {
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));
    } else {
        $logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
    }
} else {
    $logger->pushHandler(new \Monolog\Handler\NullHandler());
}

$client = new \Buckaroo\Client($logger, new \Buckaroo\HttpClient\HttpClientGuzzle($logger));
$client->setWebsiteKey($_ENV['BPE_WEBSITE_KEY']);
$client->setSecretKey($_ENV['BPE_SECRET_KEY']);
$client->setMode($_ENV['BPE_MODE']);

$app = new \Buckaroo\Example\App($logger);