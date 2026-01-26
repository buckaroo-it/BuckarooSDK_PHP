<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Debtor;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\ArticleAdapter;
use Tests\TestCase;

class CreditManagementInvoiceTest extends TestCase
{
    /** @test */
    public function it_sets_address_from_array(): void
    {
        $invoice = new Invoice([
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
        ]);

        $address = $invoice->address();

        $this->assertInstanceOf(Address::class, $address);
    }

    /** @test */
    public function it_returns_address_without_parameter(): void
    {
        $invoice = new Invoice([
            'address' => [
                'street' => 'Test Street',
            ],
        ]);

        $address = $invoice->address();
        $this->assertInstanceOf(Address::class, $address);

        $sameAddress = $invoice->address(null);
        $this->assertSame($address, $sameAddress);
    }

    /** @test */
    public function it_sets_company_from_array(): void
    {
        $invoice = new Invoice([
            'company' => [
                'name' => 'Test Company B.V.',
                'chamberOfCommerce' => '12345678',
            ],
        ]);

        $company = $invoice->company();

        $this->assertInstanceOf(Company::class, $company);
    }

    /** @test */
    public function it_returns_company_without_parameter(): void
    {
        $invoice = new Invoice([
            'company' => [
                'name' => 'Acme Corp',
            ],
        ]);

        $company = $invoice->company();
        $this->assertInstanceOf(Company::class, $company);

        $sameCompany = $invoice->company(null);
        $this->assertSame($company, $sameCompany);
    }

    /** @test */
    public function it_sets_person_from_array(): void
    {
        $invoice = new Invoice([
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $person = $invoice->person();

        $this->assertInstanceOf(Person::class, $person);
    }

    /** @test */
    public function it_returns_person_without_parameter(): void
    {
        $invoice = new Invoice([
            'person' => [
                'firstName' => 'Jane',
            ],
        ]);

        $person = $invoice->person();
        $this->assertInstanceOf(Person::class, $person);

        $samePerson = $invoice->person(null);
        $this->assertSame($person, $samePerson);
    }

    /** @test */
    public function it_sets_debtor_from_array(): void
    {
        $invoice = new Invoice([
            'debtor' => [
                'code' => 'DEBTOR001',
            ],
        ]);

        $debtor = $invoice->debtor();

        $this->assertInstanceOf(Debtor::class, $debtor);
    }

    /** @test */
    public function it_returns_debtor_without_parameter(): void
    {
        $invoice = new Invoice([
            'debtor' => [
                'code' => 'DEBTOR002',
            ],
        ]);

        $debtor = $invoice->debtor();
        $this->assertInstanceOf(Debtor::class, $debtor);

        $sameDebtor = $invoice->debtor(null);
        $this->assertSame($debtor, $sameDebtor);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $invoice = new Invoice([
            'email' => 'test@example.com',
        ]);

        $email = $invoice->email();

        $this->assertInstanceOf(Email::class, $email);
    }

    /** @test */
    public function it_returns_email_without_parameter(): void
    {
        $invoice = new Invoice([
            'email' => 'john@doe.com',
        ]);

        $email = $invoice->email();
        $this->assertInstanceOf(Email::class, $email);

        $sameEmail = $invoice->email(null);
        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $invoice = new Invoice([
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $invoice->phone();

        $this->assertInstanceOf(Phone::class, $phone);
    }

    /** @test */
    public function it_returns_phone_without_parameter(): void
    {
        $invoice = new Invoice([
            'phone' => [
                'mobile' => '0698765432',
            ],
        ]);

        $phone = $invoice->phone();
        $this->assertInstanceOf(Phone::class, $phone);

        $samePhone = $invoice->phone(null);
        $this->assertSame($phone, $samePhone);
    }

    /** @test */
    public function it_sets_articles_from_array(): void
    {
        $invoice = new Invoice([
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

        $articles = $invoice->articles();

        $this->assertIsArray($articles);
        $this->assertCount(2, $articles);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[0]);
        $this->assertInstanceOf(ArticleAdapter::class, $articles[1]);
    }

    /** @test */
    public function it_returns_empty_articles_array_without_parameter(): void
    {
        $invoice = new Invoice([]);

        $articles = $invoice->articles();

        $this->assertIsArray($articles);
        $this->assertEmpty($articles);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $invoice = new Invoice([
            'invoiceNumber' => 'INV-001',
            'invoiceAmount' => 1000.00,
            'invoiceAmountVAT' => 210.00,
            'invoiceDate' => '2024-01-15',
            'dueDate' => '2024-02-15',
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
            ],
            'company' => [
                'name' => 'Test Company',
            ],
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'debtor' => [
                'code' => 'DEBTOR001',
            ],
            'email' => 'test@example.com',
            'phone' => [
                'mobile' => '0612345678',
            ],
            'articles' => [
                ['identifier' => 'ART001', 'description' => 'Article 1', 'quantity' => 1, 'price' => 100.00],
            ],
        ]);

        $this->assertInstanceOf(Address::class, $invoice->address());
        $this->assertInstanceOf(Company::class, $invoice->company());
        $this->assertInstanceOf(Person::class, $invoice->person());
        $this->assertInstanceOf(Debtor::class, $invoice->debtor());
        $this->assertInstanceOf(Email::class, $invoice->email());
        $this->assertInstanceOf(Phone::class, $invoice->phone());
        $this->assertCount(1, $invoice->articles());
    }
}
