<?php

namespace Buckaroo\Handlers\Logging;

use Psr\Log\LoggerInterface;

interface Observer{
    public function handle(string $method, string $message, array $context = array());
}