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

use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Observer as LoggingObserver;
use Buckaroo\Handlers\Logging\Subject as LoggingSubject;
use Buckaroo\PaymentMethods\PaymentFacade;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Config;

class Buckaroo
{
    private Client $client;
    private LoggingSubject $logger;

    public function __construct(string $websiteKey, string $secretKey, string $mode = null) {
        $this->logger = new DefaultLogger();

        $this->client = new Client(new Config($websiteKey, $secretKey, $mode));
        $this->client->setLogger($this->logger);
    }

    public function payment(string $method)
    {
        return new PaymentFacade($this->client, $method);
    }

    public function attachLogger(LoggingObserver $observer)
    {
        $this->logger->attach($observer);

        return $this;
    }
}