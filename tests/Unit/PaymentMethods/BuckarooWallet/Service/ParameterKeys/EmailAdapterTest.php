<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooWallet\Service\ParameterKeys;

use Buckaroo\Models\Email;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\EmailAdapter;
use Tests\TestCase;

class EmailAdapterTest extends TestCase
{
    public function test_extends_customer_adapter(): void
    {
        $email = new Email('test@example.com');
        $adapter = new EmailAdapter($email);

        $this->assertInstanceOf(CustomerAdapter::class, $adapter);
    }

    public function test_transforms_email_to_consumer_email(): void
    {
        $email = new Email('user@example.com');
        $adapter = new EmailAdapter($email);

        $this->assertSame('ConsumerEmail', $adapter->serviceParameterKeyOf('email'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $email = new Email('john@example.com');
        $adapter = new EmailAdapter($email);

        $this->assertSame('john@example.com', $adapter->email);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $email = new Email('test@domain.com');
        $adapter = new EmailAdapter($email);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('test@domain.com', $array['email']);
    }

    public function test_handles_various_email_formats(): void
    {
        $emails = [
            'simple@example.com',
            'user+tag@example.com',
            'user.name@example.co.uk',
            'user_name@sub.example.com',
            'user123@example-domain.com',
        ];

        foreach ($emails as $emailAddress) {
            $email = new Email($emailAddress);
            $adapter = new EmailAdapter($email);

            $this->assertSame($emailAddress, $adapter->email);
            $this->assertSame('ConsumerEmail', $adapter->serviceParameterKeyOf('email'));
        }
    }
}
