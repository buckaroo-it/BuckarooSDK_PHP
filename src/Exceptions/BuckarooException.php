<?php

namespace Buckaroo\Exceptions;

use Buckaroo\Handlers\Logging\Subject;
use Exception;
use Throwable;

class BuckarooException extends Exception
{
    public function __construct(?Subject $logger, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $this->message($message);

        $this->log($logger, $message);

        parent::__construct($message, $code, $previous);
    }

    private function log($logger, $message)
    {
        if($logger)
        {
            $this->logger = $logger;
            $this->logger->error($message);
        }

        return $this;
    }

    protected function message(string $message): string
    {
        return 'Buckaroo SDKExeption: ' . $message;
    }
}