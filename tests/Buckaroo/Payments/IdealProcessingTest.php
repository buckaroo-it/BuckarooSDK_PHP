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

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;
use Buckaroo\Config\Config;


class CustomConfig extends Config
{
    public function __construct()
    {
        $websiteKey = 'Set Key';
        $secretKey = 'From other resources like DB/ENV/Platform Config';

        parent::__construct($websiteKey, $secretKey);
    }
}

class IdealProcessingTest extends BuckarooTestCase
{
    protected array $paymentPayload;
    protected function setUp(): void
    {
        $this->paymentPayload = [
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
            'pushURL' => 'https://buckaroo.dev/push',
            'returnURL' => 'https://buckaroo.dev/return',
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'customParameters' => [
                'CustomerBillingFirstName' => 'test'
            ],
            'additionalParameters' => [
                'initiated_by_magento' => 1,
                'service_action' => 'something',
            ],
        ];
    }

    /**
     * @return void
     * @test
     */
    public function it_get_idealprocessing_issuers()
    {
        $response = $this->buckaroo->method('idealprocessing')->issuers();

        $this->assertIsArray($response);
        foreach ($response as $item)
        {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
        }
    }
    
    /**
     * @return void
     * @test
     */
    public function it_creates_a_idealprocessing_payment()
    {
        $response = $this->buckaroo->method('idealprocessing')->pay($this->paymentPayload);

        $this->assertTrue($response->isPendingProcessing());
    }
}
