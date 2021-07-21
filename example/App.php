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

declare(strict_types=1);

namespace Buckaroo\SDK\Example;

use Buckaroo\SDK\Payload\PaymentResult;
use Buckaroo\SDK\Payload\TransactionResponse;
use Psr\Log\LoggerInterface;

class App
{
    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function handlePush($data)
    {
        $result = new PaymentResult($data);
        $this->logger->info(__METHOD__ . '|1|', [$result->getData()]);
    }

    public function handleResponse(TransactionResponse $response)
    {
        if ($response) {
            if ($response->hasRedirect() && $response->getRedirectUrl()) {
                if (self::isCli()) {
                    self::log('Redirect to '. $response->getRedirectUrl());
                } else {
                    header('Location: ' . $response->getRedirectUrl(), true, 302);
                }
            } else {
                self::log('Response status: '. $response->getStatusCode());
                if ($response->hasSomeError()) {
                    self::log('API description: '. $response->getSomeError());
                }
            }
        } else {
            self::log('FAILED!');
        }
    }

    public function handleException(\Exception $e)
    {
        self::log('ERROR: ' . $e->getMessage());
    }

    private function isCli()
    {
        return (php_sapi_name() == 'cli');
    }

    private function log($message)
    {
        echo "\n" . $message;
    }
}
