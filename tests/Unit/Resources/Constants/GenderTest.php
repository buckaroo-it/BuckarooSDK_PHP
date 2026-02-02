<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function test_unknown_constant(): void
    {
        $this->assertSame(0, Gender::UNKNOWN);
    }

    public function test_male_constant(): void
    {
        $this->assertSame(1, Gender::MALE);
    }

    public function test_female_constant(): void
    {
        $this->assertSame(2, Gender::FEMALE);
    }

    public function test_not_applicable_constant(): void
    {
        $this->assertSame(9, Gender::NOT_APPLICABLE);
    }

    public function test_gender_constants_are_integer_type(): void
    {
        $this->assertIsInt(Gender::UNKNOWN);
        $this->assertIsInt(Gender::MALE);
        $this->assertIsInt(Gender::FEMALE);
        $this->assertIsInt(Gender::NOT_APPLICABLE);
    }

    public function test_gender_values_are_unique(): void
    {
        $values = [
            Gender::UNKNOWN,
            Gender::MALE,
            Gender::FEMALE,
            Gender::NOT_APPLICABLE,
        ];

        $this->assertCount(4, array_unique($values));
    }
}
