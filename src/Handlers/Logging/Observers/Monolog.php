<?php

namespace Buckaroo\Handlers\Logging\Observers;

use Buckaroo\Handlers\Logging\Observer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Monolog implements Observer
{
    public function __construct()
    {
        $this->log = new Logger('Buckaroo log');
        $this->log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
    }

    public function handle(string $method, string $message, array $context = [])
    {
        $this->log->$method($message, $context);
    }
}
