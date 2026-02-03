<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Company;
use Buckaroo\Models\Person;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function test_sets_company_specific_properties(): void
    {
        $company = new Company([
            'companyName' => 'Acme Corporation',
            'vatApplicable' => true,
            'vatNumber' => 'NL123456789B01',
            'chamberOfCommerce' => '12345678',
        ]);

        $this->assertSame('Acme Corporation', $company->companyName);
        $this->assertTrue($company->vatApplicable);
        $this->assertSame('NL123456789B01', $company->vatNumber);
        $this->assertSame('12345678', $company->chamberOfCommerce);
    }

    public function test_inherits_person_properties(): void
    {
        $company = new Company([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'companyName' => 'Acme',
        ]);

        $this->assertSame('John', $company->firstName);
        $this->assertSame('Doe', $company->lastName);
        $this->assertSame('Acme', $company->companyName);
    }

    public function test_company_is_instance_of_person(): void
    {
        $company = new Company();

        $this->assertInstanceOf(Person::class, $company);
    }
    public function test_to_array_includes_all_properties(): void
    {
        $company = new Company([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'companyName' => 'Doe Enterprises',
            'vatApplicable' => true,
            'vatNumber' => 'NL111222333B01',
            'chamberOfCommerce' => '87654321',
        ]);

        $array = $company->toArray();

        $this->assertIsArray($array);
        $this->assertSame('John', $array['firstName']);
        $this->assertSame('Doe', $array['lastName']);
        $this->assertSame('Doe Enterprises', $array['companyName']);
        $this->assertTrue($array['vatApplicable']);
        $this->assertSame('NL111222333B01', $array['vatNumber']);
        $this->assertSame('87654321', $array['chamberOfCommerce']);
    }

    public function test_vat_applicable_preserves_boolean_type(): void
    {
        $companyTrue = new Company([
            'vatApplicable' => true,
        ]);

        $companyFalse = new Company([
            'vatApplicable' => false,
        ]);

        $this->assertSame(true, $companyTrue->vatApplicable);
        $this->assertSame(false, $companyFalse->vatApplicable);

        $arrayTrue = $companyTrue->toArray();
        $arrayFalse = $companyFalse->toArray();

        $this->assertSame(true, $arrayTrue['vatApplicable']);
        $this->assertSame(false, $arrayFalse['vatApplicable']);
    }

    public function test_chamber_of_commerce_nullable(): void
    {
        $companyWithChamber = new Company([
            'companyName' => 'Registered Inc',
            'chamberOfCommerce' => '12345678',
        ]);

        $companyWithoutChamber = new Company([
            'companyName' => 'Unregistered LLC',
            'chamberOfCommerce' => null,
        ]);

        $this->assertSame('12345678', $companyWithChamber->chamberOfCommerce);
    }

    public function test_person_nullable_properties_still_work(): void
    {
        $company = new Company([
            'firstName' => 'Alice',
            'lastName' => 'Johnson',
            'initials' => 'A.J.',
            'birthDate' => '1985-05-15',
            'companyName' => 'Johnson Corp',
        ]);

        $this->assertSame('A.J.', $company->initials);
        $this->assertSame('1985-05-15', $company->birthDate);

        $company->initials = null;
        $company->birthDate = null;

        $this->assertNull($company->initials);
        $this->assertNull($company->birthDate);
    }

    public function test_full_initialization(): void
    {
        $company = new Company([
            'category' => 'B2B',
            'gender' => 'M',
            'culture' => 'nl-NL',
            'careOf' => 'Finance Department',
            'title' => 'CEO',
            'initials' => 'J.D.',
            'name' => 'John Doe',
            'firstName' => 'John',
            'lastNamePrefix' => 'van',
            'lastName' => 'Doe',
            'birthDate' => '1980-01-01',
            'placeOfBirth' => 'Amsterdam',
            'companyName' => 'Doe Enterprises BV',
            'vatApplicable' => true,
            'vatNumber' => 'NL123456789B01',
            'chamberOfCommerce' => '12345678',
        ]);

        $this->assertSame('B2B', $company->category);
        $this->assertSame('M', $company->gender);
        $this->assertSame('nl-NL', $company->culture);
        $this->assertSame('Finance Department', $company->careOf);
        $this->assertSame('CEO', $company->title);
        $this->assertSame('J.D.', $company->initials);
        $this->assertSame('John Doe', $company->name);
        $this->assertSame('John', $company->firstName);
        $this->assertSame('van', $company->lastNamePrefix);
        $this->assertSame('Doe', $company->lastName);
        $this->assertSame('1980-01-01', $company->birthDate);
        $this->assertSame('Amsterdam', $company->placeOfBirth);
        $this->assertSame('Doe Enterprises BV', $company->companyName);
        $this->assertTrue($company->vatApplicable);
        $this->assertSame('NL123456789B01', $company->vatNumber);
        $this->assertSame('12345678', $company->chamberOfCommerce);
    }

    public function test_magic_get_access_to_inherited_properties(): void
    {
        $company = new Company([
            'firstName' => 'Carol',
            'lastName' => 'White',
            'title' => 'Director',
            'companyName' => 'White & Associates',
        ]);

        $this->assertSame('Carol', $company->firstName);
        $this->assertSame('White', $company->lastName);
        $this->assertSame('Director', $company->title);
        $this->assertSame('White & Associates', $company->companyName);
    }

    public function test_magic_set_on_inherited_properties(): void
    {
        $company = new Company();

        $company->firstName = 'David';
        $company->lastName = 'Green';
        $company->companyName = 'Green Solutions';
        $company->vatApplicable = true;

        $this->assertSame('David', $company->firstName);
        $this->assertSame('Green', $company->lastName);
        $this->assertSame('Green Solutions', $company->companyName);
        $this->assertTrue($company->vatApplicable);
    }

    public function test_get_object_vars_includes_both_hierarchies(): void
    {
        $company = new Company([
            'firstName' => 'Eve',
            'lastName' => 'Black',
            'companyName' => 'Black Industries',
            'vatApplicable' => true,
            'vatNumber' => 'NL999888777B01',
        ]);

        $vars = $company->getObjectVars();

        $this->assertIsArray($vars);
        $this->assertArrayHasKey('firstName', $vars);
        $this->assertArrayHasKey('lastName', $vars);
        $this->assertArrayHasKey('companyName', $vars);
        $this->assertArrayHasKey('vatApplicable', $vars);
        $this->assertArrayHasKey('vatNumber', $vars);

        $this->assertSame('Eve', $vars['firstName']);
        $this->assertSame('Black', $vars['lastName']);
        $this->assertSame('Black Industries', $vars['companyName']);
        $this->assertTrue($vars['vatApplicable']);
        $this->assertSame('NL999888777B01', $vars['vatNumber']);
    }

    public function test_set_properties_after_construction(): void
    {
        $company = new Company([
            'companyName' => 'Initial Corp',
        ]);

        $company->setProperties([
            'firstName' => 'Frank',
            'lastName' => 'Brown',
            'vatApplicable' => true,
            'vatNumber' => 'NL555666777B01',
            'chamberOfCommerce' => '99887766',
        ]);

        $this->assertSame('Initial Corp', $company->companyName);
        $this->assertSame('Frank', $company->firstName);
        $this->assertSame('Brown', $company->lastName);
        $this->assertTrue($company->vatApplicable);
        $this->assertSame('NL555666777B01', $company->vatNumber);
        $this->assertSame('99887766', $company->chamberOfCommerce);
    }

    public function test_to_array_with_minimal_data(): void
    {
        $company = new Company([
            'companyName' => 'Minimal LLC',
        ]);

        $array = $company->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Minimal LLC', $array['companyName']);
        $this->assertArrayHasKey('companyName', $array);
    }

    public function test_all_person_properties_accessible(): void
    {
        $company = new Company([
            'category' => 'Business',
            'gender' => 'F',
            'culture' => 'en-US',
            'careOf' => 'Accounting',
            'title' => 'CFO',
            'initials' => 'S.M.',
            'name' => 'Sarah Miller',
            'firstName' => 'Sarah',
            'lastNamePrefix' => 'de',
            'lastName' => 'Miller',
            'birthDate' => '1975-12-20',
            'placeOfBirth' => 'Rotterdam',
        ]);

        $this->assertSame('Business', $company->category);
        $this->assertSame('F', $company->gender);
        $this->assertSame('en-US', $company->culture);
        $this->assertSame('Accounting', $company->careOf);
        $this->assertSame('CFO', $company->title);
        $this->assertSame('S.M.', $company->initials);
        $this->assertSame('Sarah Miller', $company->name);
        $this->assertSame('Sarah', $company->firstName);
        $this->assertSame('de', $company->lastNamePrefix);
        $this->assertSame('Miller', $company->lastName);
        $this->assertSame('1975-12-20', $company->birthDate);
        $this->assertSame('Rotterdam', $company->placeOfBirth);
    }

    public function test_to_array_preserves_null_values(): void
    {
        $company = new Company([
            'companyName' => 'Null Test Corp',
            'vatApplicable' => true,
            'vatNumber' => 'NL000000000B00',
            'chamberOfCommerce' => null,
            'initials' => null,
            'birthDate' => null,
        ]);

        $array = $company->toArray();

        $this->assertNull($array['chamberOfCommerce']);
        $this->assertNull($array['initials']);
        $this->assertNull($array['birthDate']);
    }
}
