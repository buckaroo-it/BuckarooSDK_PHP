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

use Buckaroo\PaymentMethods\PaymentFacade;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

class Buckaroo
{   
    private $websiteKey, $secretKey, $mode;

    private Client $client;

    public function __construct(string $websiteKey, string $secretKey, string $mode = null) {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;
        $this->mode = ($mode) ? $mode : $_ENV['BPE_MODE'];

        $this->setDefaultClient();
    }

    private function setDefaultClient() : self
    {
        $this->client = new Client(
            $this->websiteKey,
            $this->secretKey,
        );

        $this->client->setMode($this->mode);

        return $this;
    }

    public function payment(string $method)
    {
        return new PaymentFacade($this->client, $method);
    }
}