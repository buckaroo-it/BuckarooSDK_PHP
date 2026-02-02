<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3Old\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\PaymentMethods\In3Old\Models\Pay;
use Buckaroo\PaymentMethods\In3Old\Models\Subtotal;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\CompanyAdapter;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\PhoneAdapter;
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

        $this->assertInstanceOf(Person::class, $customer);
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
        $this->assertInstanceOf(Person::class, $customer);

        // Call again without parameter should return same instance
        $sameCustomer = $pay->customer(null);
        $this->assertSame($customer, $sameCustomer);
    }

    /** @test */
    public function it_sets_company_from_array(): void
    {
        $pay = new Pay([
            'company' => [
                'name' => 'Test Company B.V.',
                'chamberOfCommerce' => '12345678',
            ],
        ]);

        $company = $pay->company();

        $this->assertInstanceOf(CompanyAdapter::class, $company);
    }

    /** @test */
    public function it_returns_company_without_parameter(): void
    {
        $pay = new Pay([
            'company' => [
                'name' => 'Acme Corp',
            ],
        ]);

        $company = $pay->company();
        $this->assertInstanceOf(CompanyAdapter::class, $company);

        // Call again without parameter
        $sameCompany = $pay->company(null);
        $this->assertSame($company, $sameCompany);
    }

    /** @test */
    public function it_sets_address_from_array(): void
    {
        $pay = new Pay([
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
        ]);

        $address = $pay->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_returns_address_without_parameter(): void
    {
        $pay = new Pay([
            'address' => [
                'street' => 'Test Street',
                'houseNumber' => '1',
            ],
        ]);

        $address = $pay->address();
        $this->assertInstanceOf(AddressAdapter::class, $address);

        // Call again without parameter
        $sameAddress = $pay->address(null);
        $this->assertSame($address, $sameAddress);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $pay = new Pay([
            'email' => 'test@example.com',
        ]);

        $email = $pay->email();

        $this->assertInstanceOf(Email::class, $email);
    }

    /** @test */
    public function it_returns_email_without_parameter(): void
    {
        $pay = new Pay([
            'email' => 'john@doe.com',
        ]);

        $email = $pay->email();
        $this->assertInstanceOf(Email::class, $email);

        // Call again without parameter
        $sameEmail = $pay->email(null);
        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $pay = new Pay([
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $pay->phone();

        $this->assertInstanceOf(PhoneAdapter::class, $phone);
    }

    /** @test */
    public function it_returns_phone_without_parameter(): void
    {
        $pay = new Pay([
            'phone' => [
                'mobile' => '0698765432',
            ],
        ]);

        $phone = $pay->phone();
        $this->assertInstanceOf(PhoneAdapter::class, $phone);

        // Call again without parameter
        $samePhone = $pay->phone(null);
        $this->assertSame($phone, $samePhone);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $pay = new Pay([
            'articles' => [
                [
                    'identifier' => 'ART001',
                    'description' => 'Test Article',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
                [
                    'identifier' => 'ART002',
                    'description' => 'Another Article',
                    'quantity' => 2,
                    'price' => 50.00,
                ],
            ],
        ]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
    }

    /** @test */
    public function it_returns_empty_articles_array_without_parameter(): void
    {
        $pay = new Pay([]);

        $articles = $pay->articles();

        $this->assertIsArray($articles);
        $this->assertEmpty($articles);
    }

    /** @test */
    public function it_sets_subtotals_from_array(): void
    {
        $pay = new Pay([
            'subtotals' => [
                [
                    'name' => 'Subtotal 1',
                    'value' => 100.00,
                ],
                [
                    'name' => 'Tax',
                    'value' => 21.00,
                ],
            ],
        ]);

        $subtotals = $pay->subtotals();

        $this->assertIsArray($subtotals);
        $this->assertCount(2, $subtotals);
        $this->assertInstanceOf(Subtotal::class, $subtotals[0]);
        $this->assertInstanceOf(Subtotal::class, $subtotals[1]);
    }

    /** @test */
    public function it_returns_empty_subtotals_array_without_parameter(): void
    {
        $pay = new Pay([]);

        $subtotals = $pay->subtotals();

        $this->assertIsArray($subtotals);
        $this->assertEmpty($subtotals);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'customerType' => 'B2C',
            'invoiceDate' => '2024-01-15',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'company' => [
                'name' => 'Test Company',
            ],
            'address' => [
                'street' => 'Test Street',
                'houseNumber' => '1',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
            'email' => 'test@example.com',
            'phone' => [
                'mobile' => '0612345678',
            ],
            'articles' => [
                ['identifier' => 'ART001', 'description' => 'Article 1', 'quantity' => 1, 'price' => 100.00],
            ],
            'subtotals' => [
                ['name' => 'Total', 'value' => 100.00],
            ],
        ]);

        $this->assertInstanceOf(Person::class, $pay->customer());
        $this->assertInstanceOf(CompanyAdapter::class, $pay->company());
        $this->assertInstanceOf(AddressAdapter::class, $pay->address());
        $this->assertInstanceOf(Email::class, $pay->email());
        $this->assertInstanceOf(PhoneAdapter::class, $pay->phone());
        $this->assertCount(1, $pay->articles());
        $this->assertCount(1, $pay->subtotals());
    }
}
