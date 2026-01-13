<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Interfaces\Recipient;
use Buckaroo\Models\Person;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function test_initializes_all_properties_correctly(): void
    {
        $person = new Person([
            'category' => 'B2C',
            'gender' => 'Male',
            'culture' => 'nl-NL',
            'careOf' => 'John Doe',
            'title' => 'Mr.',
            'initials' => 'J.D.',
            'name' => 'John Doe',
            'firstName' => 'John',
            'lastNamePrefix' => 'van',
            'lastName' => 'Doe',
            'birthDate' => '1990-05-15',
            'placeOfBirth' => 'Amsterdam',
        ]);

        $this->assertSame('B2C', $person->category);
        $this->assertSame('Male', $person->gender);
        $this->assertSame('nl-NL', $person->culture);
        $this->assertSame('John Doe', $person->careOf);
        $this->assertSame('Mr.', $person->title);
        $this->assertSame('J.D.', $person->initials);
        $this->assertSame('John Doe', $person->name);
        $this->assertSame('John', $person->firstName);
        $this->assertSame('van', $person->lastNamePrefix);
        $this->assertSame('Doe', $person->lastName);
        $this->assertSame('1990-05-15', $person->birthDate);
        $this->assertSame('Amsterdam', $person->placeOfBirth);
    }

    public function test_handles_nullable_properties(): void
    {
        $personWithNulls = new Person([
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'initials' => null,
            'birthDate' => null,
        ]);

        $personWithValues = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'initials' => 'J.D.',
            'birthDate' => '1985-03-20',
        ]);

        $this->assertSame('J.D.', $personWithValues->initials);
        $this->assertSame('1985-03-20', $personWithValues->birthDate);

        $arrayWithNulls = $personWithNulls->toArray();
        $this->assertNull($arrayWithNulls['initials']);
        $this->assertNull($arrayWithNulls['birthDate']);

        $arrayWithValues = $personWithValues->toArray();
        $this->assertSame('J.D.', $arrayWithValues['initials']);
        $this->assertSame('1985-03-20', $arrayWithValues['birthDate']);
    }

    public function test_implements_recipient_interface(): void
    {
        $person = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);

        $this->assertInstanceOf(Recipient::class, $person);
    }

    public function test_handles_name_prefixes(): void
    {
        $dutchPrefixes = [
            'van',
            'de',
            'van der',
            'van den',
            'van de',
            'ter',
            'te',
            'den',
            'der',
        ];

        foreach ($dutchPrefixes as $prefix)
        {
            $person = new Person([
                'firstName' => 'Jan',
                'lastNamePrefix' => $prefix,
                'lastName' => 'Berg',
            ]);

            $this->assertSame($prefix, $person->lastNamePrefix);
            $this->assertSame('Berg', $person->lastName);

            $array = $person->toArray();
            $this->assertSame($prefix, $array['lastNamePrefix']);
        }
    }

    public function test_handles_special_characters_in_names(): void
    {
        $person = new Person([
            'firstName' => "Anne-Marie",
            'lastName' => "O'Connor",
            'name' => "Anne-Marie O'Connor",
            'culture' => 'en-IE',
        ]);

        $this->assertSame("Anne-Marie", $person->firstName);
        $this->assertSame("O'Connor", $person->lastName);

        $accentedPerson = new Person([
            'firstName' => 'François',
            'lastName' => 'Müller',
            'culture' => 'fr-FR',
        ]);

        $this->assertSame('François', $accentedPerson->firstName);
        $this->assertSame('Müller', $accentedPerson->lastName);

        $array = $accentedPerson->toArray();
        $this->assertSame('François', $array['firstName']);
        $this->assertSame('Müller', $array['lastName']);
    }

    public function test_handles_unicode_names(): void
    {
        $arabicPerson = new Person([
            'firstName' => 'محمد',
            'lastName' => 'علي',
            'culture' => 'ar-SA',
        ]);

        $this->assertSame('محمد', $arabicPerson->firstName);
        $this->assertSame('علي', $arabicPerson->lastName);

        $chinesePerson = new Person([
            'firstName' => '伟',
            'lastName' => '王',
            'culture' => 'zh-CN',
        ]);

        $this->assertSame('伟', $chinesePerson->firstName);
        $this->assertSame('王', $chinesePerson->lastName);

        $cyrillicPerson = new Person([
            'firstName' => 'Иван',
            'lastName' => 'Петров',
            'culture' => 'ru-RU',
        ]);

        $this->assertSame('Иван', $cyrillicPerson->firstName);
        $this->assertSame('Петров', $cyrillicPerson->lastName);

        $arabicArray = $arabicPerson->toArray();
        $this->assertSame('محمد', $arabicArray['firstName']);
        $this->assertSame('علي', $arabicArray['lastName']);
    }

    public function test_handles_partial_initialization(): void
    {
        $minimalPerson = new Person([
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);

        $this->assertSame('John', $minimalPerson->firstName);
        $this->assertSame('Doe', $minimalPerson->lastName);

        $fullPerson = new Person([
            'category' => 'B2B',
            'gender' => 'Female',
            'culture' => 'en-US',
            'careOf' => 'Jane Smith',
            'title' => 'Dr.',
            'initials' => 'J.S.',
            'name' => 'Dr. Jane Smith',
            'firstName' => 'Jane',
            'lastNamePrefix' => 'von',
            'lastName' => 'Smith',
            'birthDate' => '1988-12-01',
            'placeOfBirth' => 'New York',
        ]);

        $this->assertSame('B2B', $fullPerson->category);
        $this->assertSame('Female', $fullPerson->gender);
        $this->assertSame('Dr.', $fullPerson->title);
        $this->assertSame('1988-12-01', $fullPerson->birthDate);
    }

    public function test_to_array_preserves_all_values_including_edge_cases(): void
    {
        $person = new Person([
            'category' => 'B2C',
            'gender' => 'Male',
            'culture' => 'nl-NL',
            'careOf' => 'T.A. Parent',
            'title' => 'Drs.',
            'initials' => 'P.J.M.',
            'name' => 'Pieter Jan Maria van den Berg',
            'firstName' => 'Pieter Jan Maria',
            'lastNamePrefix' => 'van den',
            'lastName' => 'Berg',
            'birthDate' => '1975-11-23',
            'placeOfBirth' => "'s-Gravenhage",
        ]);

        $array = $person->toArray();

        $this->assertIsArray($array);
        $this->assertSame('B2C', $array['category']);
        $this->assertSame('Male', $array['gender']);
        $this->assertSame('nl-NL', $array['culture']);
        $this->assertSame('T.A. Parent', $array['careOf']);
        $this->assertSame('Drs.', $array['title']);
        $this->assertSame('P.J.M.', $array['initials']);
        $this->assertSame('Pieter Jan Maria van den Berg', $array['name']);
        $this->assertSame('Pieter Jan Maria', $array['firstName']);
        $this->assertSame('van den', $array['lastNamePrefix']);
        $this->assertSame('Berg', $array['lastName']);
        $this->assertSame('1975-11-23', $array['birthDate']);
        $this->assertSame("'s-Gravenhage", $array['placeOfBirth']);
    }
}
