<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\RecipientCategory;
use PHPUnit\Framework\TestCase;

class RecipientCategoryTest extends TestCase
{
    public function test_person_constant(): void
    {
        $this->assertSame('Person', RecipientCategory::PERSON);
    }

    public function test_company_constant(): void
    {
        $this->assertSame('Company', RecipientCategory::COMPANY);
    }

    public function test_constants_are_string_type(): void
    {
        $this->assertIsString(RecipientCategory::PERSON);
        $this->assertIsString(RecipientCategory::COMPANY);
    }

    public function test_constants_are_capitalized(): void
    {
        $this->assertMatchesRegularExpression('/^[A-Z]/', RecipientCategory::PERSON);
        $this->assertMatchesRegularExpression('/^[A-Z]/', RecipientCategory::COMPANY);
    }
}
