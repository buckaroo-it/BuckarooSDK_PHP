<?php

namespace Buckaroo\Handlers\Logging;

use Buckaroo\Handlers\Logging\Observers\ErrorReporter;
use Buckaroo\Handlers\Logging\Observers\Monolog;

class DefaultLogger implements Subject
{
    protected array $observers  = [];

    public function __construct() {
        $this->attach(new Monolog());

        if(($_ENV['BPE_REPORT_ERROR'] ?? false) === 'true') {
            $this->attach(new ErrorReporter());
        }
    }

    public function attach($observer)
    {
        if(is_array($observer)) {
            foreach($observer as $singleObserver) {
                $this->attach($singleObserver);
            }

            return $this;
        }

        if($observer instanceof Observer) {
            $this->observers[] = $observer;
        }

        return $this;
    }

    public function detach(Observer $observer)
    {
        $this->observers = array_filter($this->observers, function($value) use ($observer){
           return get_class($value) != get_class($observer);
        });

        return $this;
    }

    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->notify('emergency', $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->notify('alert', $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->notify('critical', $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->notify('error', $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->notify('warning', $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->notify('notice', $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->notify('info', $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        if($_ENV['BPE_DEBUG'] ?? false) {
            $this->notify('debug', $message, $context);
        }
    }

    public function log($level, \Stringable|string $message, array $context = array()): void
    {
        $this->notify('log', $message, $context);
    }

    public function notify(string $method, string $message, array $context = array())
    {
        foreach($this->observers as $observer) {
            $observer->handle($method, $message, $context);
        }

        return $this;
    }
}
