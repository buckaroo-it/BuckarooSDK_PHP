<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\Models\Debtor;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\PaymentMethods\Subscriptions\Models\Configuration;
use Buckaroo\PaymentMethods\Subscriptions\Models\RatePlan;
use Buckaroo\PaymentMethods\Subscriptions\Models\RatePlanCharge;
use Buckaroo\PaymentMethods\Subscriptions\Models\Subscription;
use Buckaroo\PaymentMethods\Subscriptions\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Subscriptions\Service\ParameterKeys\BankAccountAdapter;
use Buckaroo\PaymentMethods\Subscriptions\Service\ParameterKeys\CompanyAdapter;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /** @test */
    public function it_sets_debtor_from_array(): void
    {
        $subscription = new Subscription([
            'debtor' => [
                'code' => 'DEBTOR001',
            ],
        ]);

        $debtor = $subscription->debtor();

        $this->assertInstanceOf(Debtor::class, $debtor);
    }

    /** @test */
    public function it_returns_debtor_without_parameter(): void
    {
        $subscription = new Subscription([
            'debtor' => [
                'code' => 'DEBTOR002',
            ],
        ]);

        $debtor = $subscription->debtor();
        $this->assertInstanceOf(Debtor::class, $debtor);

        $sameDebtor = $subscription->debtor(null);
        $this->assertSame($debtor, $sameDebtor);
    }

    /** @test */
    public function it_sets_bank_account_from_array(): void
    {
        $subscription = new Subscription([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
                'bic' => 'ABNANL2A',
                'accountName' => 'John Doe',
            ],
        ]);

        $bankAccount = $subscription->bankAccount();

        $this->assertInstanceOf(BankAccountAdapter::class, $bankAccount);
    }

    /** @test */
    public function it_returns_bank_account_without_parameter(): void
    {
        $subscription = new Subscription([
            'bankAccount' => [
                'iban' => 'NL91ABNA0417164300',
            ],
        ]);

        $bankAccount = $subscription->bankAccount();
        $this->assertInstanceOf(BankAccountAdapter::class, $bankAccount);

        $sameBankAccount = $subscription->bankAccount(null);
        $this->assertSame($bankAccount, $sameBankAccount);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $subscription = new Subscription([
            'email' => 'test@example.com',
        ]);

        $email = $subscription->email();

        $this->assertInstanceOf(Email::class, $email);
    }

    /** @test */
    public function it_returns_email_without_parameter(): void
    {
        $subscription = new Subscription([
            'email' => 'john@doe.com',
        ]);

        $email = $subscription->email();
        $this->assertInstanceOf(Email::class, $email);

        $sameEmail = $subscription->email(null);
        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_phone_from_array(): void
    {
        $subscription = new Subscription([
            'phone' => [
                'mobile' => '0612345678',
            ],
        ]);

        $phone = $subscription->phone();

        $this->assertInstanceOf(Phone::class, $phone);
    }

    /** @test */
    public function it_returns_phone_without_parameter(): void
    {
        $subscription = new Subscription([
            'phone' => [
                'mobile' => '0698765432',
            ],
        ]);

        $phone = $subscription->phone();
        $this->assertInstanceOf(Phone::class, $phone);

        $samePhone = $subscription->phone(null);
        $this->assertSame($phone, $samePhone);
    }

    /** @test */
    public function it_sets_address_from_array(): void
    {
        $subscription = new Subscription([
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
                'zipcode' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
            ],
        ]);

        $address = $subscription->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_returns_address_without_parameter(): void
    {
        $subscription = new Subscription([
            'address' => [
                'street' => 'Test Street',
            ],
        ]);

        $address = $subscription->address();
        $this->assertInstanceOf(AddressAdapter::class, $address);

        $sameAddress = $subscription->address(null);
        $this->assertSame($address, $sameAddress);
    }

    /** @test */
    public function it_sets_person_from_array(): void
    {
        $subscription = new Subscription([
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $person = $subscription->person();

        $this->assertInstanceOf(Person::class, $person);
    }

    /** @test */
    public function it_returns_person_without_parameter(): void
    {
        $subscription = new Subscription([
            'person' => [
                'firstName' => 'Jane',
            ],
        ]);

        $person = $subscription->person();
        $this->assertInstanceOf(Person::class, $person);

        $samePerson = $subscription->person(null);
        $this->assertSame($person, $samePerson);
    }

    /** @test */
    public function it_sets_company_from_array(): void
    {
        $subscription = new Subscription([
            'company' => [
                'name' => 'Test Company B.V.',
                'chamberOfCommerce' => '12345678',
            ],
        ]);

        $company = $subscription->company();

        $this->assertInstanceOf(CompanyAdapter::class, $company);
    }

    /** @test */
    public function it_returns_company_without_parameter(): void
    {
        $subscription = new Subscription([
            'company' => [
                'name' => 'Acme Corp',
            ],
        ]);

        $company = $subscription->company();
        $this->assertInstanceOf(CompanyAdapter::class, $company);

        $sameCompany = $subscription->company(null);
        $this->assertSame($company, $sameCompany);
    }

    /** @test */
    public function it_sets_configuration_from_array(): void
    {
        $subscription = new Subscription([
            'configuration' => [
                'name' => 'Test Configuration',
            ],
        ]);

        $configuration = $subscription->configuration();

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    /** @test */
    public function it_returns_configuration_without_parameter(): void
    {
        $subscription = new Subscription([
            'configuration' => [
                'name' => 'Config',
            ],
        ]);

        $configuration = $subscription->configuration();
        $this->assertInstanceOf(Configuration::class, $configuration);

        $sameConfiguration = $subscription->configuration(null);
        $this->assertSame($configuration, $sameConfiguration);
    }

    /** @test */
    public function it_sets_rate_plans_from_array(): void
    {
        $subscription = new Subscription([
            'ratePlans' => [
                'add' => [
                    'ratePlanCode' => 'PLAN001',
                ],
                'update' => [
                    'ratePlanCode' => 'PLAN002',
                ],
                'disable' => [
                    'ratePlanCode' => 'PLAN003',
                ],
            ],
        ]);

        $result = $subscription->ratePlans();

        $this->assertSame($subscription, $result);
    }

    /** @test */
    public function it_returns_self_for_rate_plans_without_parameter(): void
    {
        $subscription = new Subscription([]);

        $result = $subscription->ratePlans();

        $this->assertSame($subscription, $result);
    }

    /** @test */
    public function it_sets_rate_plan_charges_from_array(): void
    {
        $subscription = new Subscription([
            'ratePlanCharges' => [
                'add' => [
                    'ratePlanChargeCode' => 'CHARGE001',
                ],
                'update' => [
                    'ratePlanChargeCode' => 'CHARGE002',
                ],
            ],
        ]);

        $result = $subscription->ratePlanCharges();

        $this->assertSame($subscription, $result);
    }

    /** @test */
    public function it_returns_self_for_rate_plan_charges_without_parameter(): void
    {
        $subscription = new Subscription([]);

        $result = $subscription->ratePlanCharges();

        $this->assertSame($subscription, $result);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $subscription = new Subscription([
            'includeTransaction' => true,
            'transactionVatPercentage' => 21.0,
            'configurationCode' => 'CONFIG001',
            'debtor' => ['code' => 'DEBTOR001'],
            'bankAccount' => ['iban' => 'NL91ABNA0417164300'],
            'email' => 'test@example.com',
            'phone' => ['mobile' => '0612345678'],
            'address' => ['street' => 'Main Street', 'houseNumber' => '123'],
            'person' => ['firstName' => 'John', 'lastName' => 'Doe'],
            'company' => ['name' => 'Test Company'],
            'configuration' => ['name' => 'Test Config'],
        ]);

        $this->assertInstanceOf(Debtor::class, $subscription->debtor());
        $this->assertInstanceOf(BankAccountAdapter::class, $subscription->bankAccount());
        $this->assertInstanceOf(Email::class, $subscription->email());
        $this->assertInstanceOf(Phone::class, $subscription->phone());
        $this->assertInstanceOf(AddressAdapter::class, $subscription->address());
        $this->assertInstanceOf(Person::class, $subscription->person());
        $this->assertInstanceOf(CompanyAdapter::class, $subscription->company());
        $this->assertInstanceOf(Configuration::class, $subscription->configuration());
    }
}
