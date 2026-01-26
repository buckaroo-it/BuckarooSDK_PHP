<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3\Models;

use Buckaroo\Models\Email;
use Buckaroo\PaymentMethods\In3\Models\Recipient;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    /** @test */
    public function it_creates_b2c_person_recipient(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'category' => 'B2C',
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
    }

    /** @test */
    public function it_creates_b2b_company_recipient(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'category' => 'B2B',
                'companyName' => 'ACME Inc.',
            ],
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
    }

    /** @test */
    public function it_throws_exception_for_invalid_recipient_category(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No recipient category found.');

        new Recipient('Billing', [
            'recipient' => [
                'category' => 'invalid',
            ],
        ]);
    }

    /** @test */
    public function it_throws_exception_when_category_is_missing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No recipient category found.');

        new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
            ],
        ]);
    }

    /** @test */
    public function it_sets_and_returns_address_adapter(): void
    {
        $recipient = new Recipient('Billing', [
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
            ],
        ]);

        $this->assertInstanceOf(AddressAdapter::class, $recipient->address());
    }

    /** @test */
    public function it_sets_and_returns_phone_adapter(): void
    {
        $recipient = new Recipient('Billing', [
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $this->assertInstanceOf(PhoneAdapter::class, $recipient->phone());
    }

    /** @test */
    public function it_sets_and_returns_email(): void
    {
        $recipient = new Recipient('Billing', [
            'email' => 'john@example.com',
        ]);

        $this->assertInstanceOf(Email::class, $recipient->email());
    }

    /** @test */
    public function it_returns_group_type_with_customer_suffix(): void
    {
        $billingRecipient = new Recipient('Billing', []);
        $shippingRecipient = new Recipient('Shipping', []);

        $this->assertSame('BillingCustomer', $billingRecipient->getGroupType('any'));
        $this->assertSame('ShippingCustomer', $shippingRecipient->getGroupType('any'));
    }
}
