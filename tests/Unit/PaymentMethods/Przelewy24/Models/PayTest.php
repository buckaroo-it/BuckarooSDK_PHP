<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Przelewy24\Models;

use Buckaroo\PaymentMethods\Przelewy24\Models\Pay;
use Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\Przelewy24\Service\ParameterKeys\EmailAdapter;
use Tests\TestCase;

class PayTest extends TestCase
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
    public function it_sets_email_from_string(): void
    {
        $pay = new Pay([
            'email' => 'customer@example.com',
        ]);

        $email = $pay->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'customer' => ['firstName' => 'Jane', 'lastName' => 'Smith'],
            'email' => 'jane@example.com',
        ]);

        $this->assertInstanceOf(CustomerAdapter::class, $pay->customer());
        $this->assertInstanceOf(EmailAdapter::class, $pay->email());
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
