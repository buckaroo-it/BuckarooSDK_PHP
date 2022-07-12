<?php

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
        $response = $this->buckaroo->payment('creditcard')->pay([
            'amountDebit'   => 10,
            'invoice'       => uniqid(),
            'name'          => 'visa'
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_payment()
    {
        $response = $this->buckaroo->payment('creditcard')->payEncrypted([
            'amountDebit'               => 10,
            'invoice'                   => uniqid(),
            'name'                      => 'mastercard',
            'encryptedCardData'         => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_payment()
    {
        $response = $this->buckaroo->payment('creditcard')->payWithSecurityCode([
            'amountDebit'                   => 10,
            'invoice'                       => uniqid(),
            'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
            'name'                          => 'mastercard',
            'encryptedSecurityCode'         => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_refund()
    {
        $response = $this->buckaroo->payment('creditcard')->refund([
            'amountCredit'                  => 10,
            'invoice'                       => 'testinvoice 123',
            'originalTransactionKey'        => '13FAF43579D94F5FB8119A6819XXXXXX',
            'name'                          => 'mastercard'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_authorize()
    {
        $response = $this->buckaroo->payment('creditcard')->authorize([
            'amountDebit'   => 10,
            'invoice'       => 'testinvoice 123',
            'name'          => 'mastercard'
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_encrypted_authorize()
    {
        $response = $this->buckaroo->payment('creditcard')->authorizeEncrypted([
            'amountDebit'           => 10,
            'invoice'               => uniqid(),
            'name'                  => 'mastercard',
            'encryptedCardData'     => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_security_code_authorize()
    {
        $response = $this->buckaroo->payment('creditcard')->authorizeWithSecurityCode([
            'amountDebit'               => 10,
            'invoice'                   => uniqid(),
            'originalTransactionKey'    => '6C5DBB69E74644958F8C25199514DC6C',
            'name'                      => 'mastercard',
            'encryptedSecurityCode'     => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_capture()
    {
        $response = $this->buckaroo->payment('creditcard')->capture([
            'amountDebit'                   => 10,
            'invoice'                       => uniqid(),
            'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
            'name'                          => 'mastercard'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditcard_pay_recurrent()
    {
        $response = $this->buckaroo->payment('creditcard')->payRecurrent([
            'amountDebit'                   => 10,
            'invoice'                       => uniqid(),
            'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
            'name'                          => 'mastercard'
        ]);

        $this->assertTrue($response->isSuccess());
    }
}