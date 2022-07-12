<?php

/**
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

namespace Buckaroo;

use Buckaroo\Config\Config;
use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Observer as LoggingObserver;
use Buckaroo\Handlers\Logging\Subject as LoggingSubject;
use Buckaroo\PaymentMethods\PaymentFacade;
use Buckaroo\Transaction\Client;

/**
 *
 */
class Buckaroo
{
    /**
     * @var Client
     */
    private Client $client;
    /**
     * @var LoggingSubject|DefaultLogger
     */
    private LoggingSubject $logger;

    /**
     * @param string $websiteKey
     * @param string $secretKey
     * @param string|null $mode
     */
    public function __construct(string $websiteKey, string $secretKey, string $mode = null) {
        $this->logger = new DefaultLogger();

        $config = $this->getConfig($websiteKey, $secretKey, $mode);

        $this->client = new Client($config);
        $this->client->setLogger($this->logger);
    }

    /**
     * @param string $method
     * @return PaymentFacade
     */
    public function payment(string $method)
    {
        return new PaymentFacade($this->client, $method);
    }

    /**
     * @param LoggingObserver $observer
     * @return $this
     */
    public function attachLogger(LoggingObserver $observer)
    {
        $this->logger->attach($observer);

        return $this;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->client->config($config);

        return $this;
    }

    public function client()
    {
        return $this->client;
    }

    /**
     * @param string $websiteKey
     * @param string $secretKey
     * @param string|null $mode
     * @return Config|null
     */
    private function getConfig(string $websiteKey, string $secretKey, string $mode = null): ?Config
    {
        if($websiteKey && $secretKey)
        {
            return new DefaultConfig($websiteKey, $secretKey, $mode);
        }

        return null;
    }
}
