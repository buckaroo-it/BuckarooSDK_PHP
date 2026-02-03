<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Recipient;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    /** @test */
    public function it_requires_type_in_constructor(): void
    {
        $recipient = new Recipient('Billing', []);

        $this->assertInstanceOf(Recipient::class, $recipient);
    }

    /** @test */
    public function it_sets_recipient_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $result = $recipient->recipient();

        $this->assertInstanceOf(RecipientAdapter::class, $result);
    }

    /** @test */
    public function it_sets_address_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
            ],
        ]);

        $address = $recipient->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $recipient = new Recipient('Billing', [
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $recipient->phone();

        $this->assertInstanceOf(PhoneAdapter::class, $phone);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $recipient = new Recipient('Billing', [
            'email' => 'test@example.com',
        ]);

        $email = $recipient->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_handles_empty_values(): void
    {
        $recipient = new Recipient('Shipping', []);

        $array = $recipient->toArray();
        $this->assertIsArray($array);
    }
}
