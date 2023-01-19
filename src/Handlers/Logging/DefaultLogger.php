<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Buckaroo\Handlers\Logging;

use Buckaroo\Handlers\Logging\Observers\ErrorReporter;
use Buckaroo\Handlers\Logging\Observers\Monolog;
use Psr\Log\LoggerInterface;
use Stringable;

class DefaultLogger implements Subject, LoggerInterface
{
    /**
     * @var array
     */
    protected array $observers = [];

    /**
     *
     */
    public function __construct()
    {
        $this->attach(new Monolog());

        if (($_ENV['BPE_REPORT_ERROR'] ?? false) === 'true')
        {
            $this->attach(new ErrorReporter());
        }
    }

    /**
     * @param $observer
     * @return $this
     */
    public function attach($observer)
    {
        if (is_array($observer))
        {
            foreach ($observer as $singleObserver)
            {
                $this->attach($singleObserver);
            }

            return $this;
        }

        if ($observer instanceof Observer)
        {
            $this->observers[] = $observer;
        }

        return $this;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function detach(Observer $observer)
    {
        $this->observers = array_filter($this->observers, function ($value) use ($observer)
        {
            return get_class($value) != get_class($observer);
        });

        return $this;
    }

    /**
     * @param string|Stringable $message The log message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->notify('emergency', $message, $context);
    }

    /**
     * @param string|Stringable $message The log message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->notify('alert', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->notify('critical', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->notify('error', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->notify('warning', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->notify('notice', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->notify('info', $message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        if ($_ENV['BPE_DEBUG'] ?? false)
        {
            $this->notify('debug', $message, $context);
        }
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->notify('log', $message, $context);
    }

    /**
     * @param string $method
     * @param string $message
     * @param array $context
     * @return $this
     */
    public function notify(string $method, string $message, array $context = [])
    {
        foreach ($this->observers as $observer)
        {
            $observer->handle($method, $message, $context);
        }

        return $this;
    }
}
