<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\PaymentMethods\BankTransfer\Models\Pay;
use Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BankTransfer\Service\ParameterKeys\EmailAdapter;
use Tests\TestCase;

class BankTransferPayTest extends TestCase
{
    /** @test */
    public function it_sets_customer_from_array(): void
    {
        $pay = new Pay([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $customer = $pay->customer();

        $this->assertInstanceOf(CustomerAdapter::class, $customer);
    }

    /** @test */
    public function it_returns_customer_without_parameter(): void
    {
        $pay = new Pay([
            'customer' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
        ]);

        $customer = $pay->customer();
        $this->assertInstanceOf(CustomerAdapter::class, $customer);

        // Call again without parameter should return same instance
        $sameCustomer = $pay->customer(null);
        $this->assertSame($customer, $sameCustomer);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $pay = new Pay([
            'email' => 'test@example.com',
        ]);

        $email = $pay->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_returns_email_without_parameter(): void
    {
        $pay = new Pay([
            'email' => 'john@doe.com',
        ]);

        $email = $pay->email();
        $this->assertInstanceOf(EmailAdapter::class, $email);

        // Call again without parameter
        $sameEmail = $pay->email(null);
        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'sendMail' => true,
            'dateDue' => '2024-02-15',
            'country' => 'NL',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'email' => 'test@example.com',
        ]);

        $this->assertInstanceOf(CustomerAdapter::class, $pay->customer());
        $this->assertInstanceOf(EmailAdapter::class, $pay->email());
    }
}
