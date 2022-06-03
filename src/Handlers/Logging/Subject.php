<?php

namespace Buckaroo\Handlers\Logging;

use Psr\Log\LoggerInterface;

interface Subject extends LoggerInterface
{
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify(string $method, string $message, array $context = array());
}