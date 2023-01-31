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

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class CreditcardTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_payment()
    {
        $response = $this->buckaroo->method('creditcard')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'name' => 'visa',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_payment()
    {
        $response = $this->buckaroo->method('creditcard')->payEncrypted([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'name' => 'mastercard',
            'encryptedCardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_payment()
    {
        $response = $this->buckaroo->method('creditcard')->payWithSecurityCode([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
            'name' => 'mastercard',
            'encryptedSecurityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_refund()
    {
        $response = $this->buckaroo->method('creditcard')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'originalTransactionKey' => '13FAF43579D94F5FB8119A6819XXXXXX',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorize([
            'amountDebit' => 10,
            'invoice' => 'testinvoice 123',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorizeEncrypted([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'name' => 'mastercard',
            'encryptedCardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->authorizeWithSecurityCode([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
            'name' => 'mastercard',
            'encryptedSecurityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_capture()
    {
        $response = $this->buckaroo->method('creditcard')->capture([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_pay_recurrent()
    {
        $response = $this->buckaroo->method('creditcard')->payRecurrent([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
            'name' => 'mastercard',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_cancel_authorize()
    {
        $response = $this->buckaroo->method('creditcard')->cancelAuthorize([
            'name' => 'mastercard',
            'amountCredit' => 10,
            'originalTransactionKey' => 'F86579ECED1D493887ECAE7C287BXXXX',
            'invoice' => 'testinvoice12345cvx',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}
