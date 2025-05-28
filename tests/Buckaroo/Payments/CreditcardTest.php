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

class CreditcardTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_payment()
    {
        $response = $this->buckaroo->method('creditcard')->pay($this->getBasePayPayload([],[
            'name' => 'visa',
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_payment()
    {
        $response = $this->buckaroo->method('creditcard')->payEncrypted($this->getBasePayPayload([],[
            'name' => 'mastercard',
            'encryptedCardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z+x0jCw6NjzbrweVQhBRkrbs7TBJkS4tR38JiDsXyH2E1JmRHE+o2P9qz4at6w3zggmwImvjt5IIjEr6g8KfsIDXfv7YjEzhJ3P+7uuGoyG2WYm/Pr0+iEmTj5Q/ijkxu1+cDqv5eiB+80KgffPItUZDrnv9sKlVBAr+f53nm1G+Sxp0Q==',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_payment()
    {
        $response = $this->buckaroo->method('creditcard')->payWithSecurityCode($this->getBasePayPayload([],[
            'originalTransactionKey' => '4D81D54DAA77407689208A7609795B8F',
            'name' => 'mastercard',
            'encryptedSecurityCode' => '001F3AJT7wkJa04zE8c78P7spOAgHSKH1YKgPlOwXhW049VfIXMwZO32RYna9xZRyUCtfODIoCL8GRQoaZbStlBT4rbF5e4PPvWFSKdvua4rq+GQDNAghfa+ZQz0BzBPfjS0WBdFape9n3zH2vC/0m+wI3QZiDpYYgyWC1/Y3udJDU7JRTVMq/BDHGet+IZ2CDnkeGl813kkYymzYon/QeuQRQ0Wsec5bmVQNYGx62fz70/vLgs0ffff+6DtZtnZWfByRkTwMNebJotlOsSkbhVR5FrHpAbNPCJI+LvJcJL7Eoo+ZuX5/LWGmsT6qnR/uLiIw1DI7mTKGy6/P7IljAE+g==',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_refund()
    {
        $response = $this->buckaroo->method('creditcard')->refund($this->getRefundPayload([
            'originalTransactionKey' => '3D40E227D336441689092DDFC388810B',
            'name' => 'mastercard',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorize($this->getBasePayPayload([],[
            'name' => 'mastercard',
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorizeEncrypted($this->getBasePayPayload([],[
            'name' => 'mastercard',
            'encryptedCardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z+x0jCw6NjzbrweVQhBRkrbs7TBJkS4tR38JiDsXyH2E1JmRHE+o2P9qz4at6w3zggmwImvjt5IIjEr6g8KfsIDXfv7YjEzhJ3P+7uuGoyG2WYm/Pr0+iEmTj5Q/ijkxu1+cDqv5eiB+80KgffPItUZDrnv9sKlVBAr+f53nm1G+Sxp0Q==',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    // 492 - Technical failure
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorizeWithSecurityCode($this->getBasePayPayload([],[
            'originalTransactionKey' => '4D81D54DAA77407689208A7609795B8F',
            'name' => 'mastercard',
            'encryptedSecurityCode' => '001F3AJT7wkJa04zE8c78P7spOAgHSKH1YKgPlOwXhW049VfIXMwZO32RYna9xZRyUCtfODIoCL8GRQoaZbStlBT4rbF5e4PPvWFSKdvua4rq+GQDNAghfa+ZQz0BzBPfjS0WBdFape9n3zH2vC/0m+wI3QZiDpYYgyWC1/Y3udJDU7JRTVMq/BDHGet+IZ2CDnkeGl813kkYymzYon/QeuQRQ0Wsec5bmVQNYGx62fz70/vLgs0ffff+6DtZtnZWfByRkTwMNebJotlOsSkbhVR5FrHpAbNPCJI+LvJcJL7Eoo+ZuX5/LWGmsT6qnR/uLiIw1DI7mTKGy6/P7IljAE+g==',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_capture()
    {
        $response = $this->buckaroo->method('creditcard')->capture($this->getBasePayPayload([],[
            'originalTransactionKey' => '78ADA073DAD74E14BFC83EE308B70374',
            'name' => 'mastercard',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_pay_recurrent()
    {
        $response = $this->buckaroo->method('creditcard')->payRecurrent($this->getBasePayPayload([],[
            'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
            'name' => 'mastercard',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    // 491 - Validation failure (no info)
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_cancel_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->cancelAuthorize($this->getBasePayPayload([],[
            'originalTransactionKey' => '41733E4210684988939CEE58AC899602',
            'name' => 'mastercard',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_token_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorizeWithToken($this->getBasePayPayload([],[
            'name' => 'visa',
            'sessionId' => 'hf_43ab37u53XIDOpJg',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }


    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_hosted_fields_payment()
    {
        $response = $this->buckaroo->method('creditcard')->payWithToken($this->getBasePayPayload([],[
            'name' => 'visa',
            'sessionId' => 'hf_43ab37u53XIDOpJg',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }
}
