<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Buckaroo\HttpClient\HttpClientGuzzle;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Psr\Log\LoggerInterface;

class DefaultFactory
{
    public static function getDefaultLogger(): LoggerInterface
    {
        $logger = new Logger('buckaroo-sdk');
        $logger->pushHandler(new NullHandler());

        return $logger;
    }

    public static function getDefaultHttpClient(LoggerInterface $logger): HttpClientGuzzle
    {
        return new HttpClientGuzzle($logger);
    }
}
