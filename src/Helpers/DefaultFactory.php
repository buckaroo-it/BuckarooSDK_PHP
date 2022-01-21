<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Buckaroo\Client;
use Buckaroo\HttpClient\HttpClientGuzzle;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class DefaultFactory
{
    public static function getDefaultLogger()
    {
        $logger = new Logger('buckaroo-sdk');
        $logger->pushHandler(new NullHandler());

        return $logger;
    }

    public static function getDefaultHttpClient($logger)
    {
        return new HttpClientGuzzle($logger);
    }
}
