<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Debtor;
use Tests\TestCase;

class DebtorTest extends TestCase
{
    public function test_initializes_code_property(): void
    {
        $debtor = new Debtor([
            'code' => 'DEBTOR-12345',
        ]);

        $this->assertSame('DEBTOR-12345', $debtor->code);
    }

    public function test_to_array_includes_code(): void
    {
        $debtor = new Debtor([
            'code' => 'DBT-001',
        ]);

        $array = $debtor->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertIsString($array['code']);
        $this->assertSame('DBT-001', $array['code']);
    }

    public function test_handles_various_code_formats(): void
    {
        $alphanumeric = new Debtor(['code' => 'ABC123']);
        $this->assertSame('ABC123', $alphanumeric->code);
        $this->assertSame('ABC123', $alphanumeric->toArray()['code']);

        $withDashes = new Debtor(['code' => 'DEBTOR-2024-001']);
        $this->assertSame('DEBTOR-2024-001', $withDashes->code);
        $this->assertSame('DEBTOR-2024-001', $withDashes->toArray()['code']);

        $uppercase = new Debtor(['code' => 'UPPERCASE']);
        $this->assertSame('UPPERCASE', $uppercase->code);

        $lowercase = new Debtor(['code' => 'lowercase']);
        $this->assertSame('lowercase', $lowercase->code);

        $numeric = new Debtor(['code' => '123456']);
        $this->assertSame('123456', $numeric->code);
    }

    public function test_handles_empty_and_special_characters_in_code(): void
    {
        $empty = new Debtor(['code' => '']);
        $this->assertSame('', $empty->code);
        $this->assertSame('', $empty->toArray()['code']);

        $specialChars = new Debtor(['code' => 'CODE_#123@SPECIAL!']);
        $this->assertSame('CODE_#123@SPECIAL!', $specialChars->code);
        $this->assertSame('CODE_#123@SPECIAL!', $specialChars->toArray()['code']);

        $unicode = new Debtor(['code' => 'DÉBTOR-€100']);
        $this->assertSame('DÉBTOR-€100', $unicode->code);
        $this->assertSame('DÉBTOR-€100', $unicode->toArray()['code']);

        $whitespace = new Debtor(['code' => '  CODE WITH SPACES  ']);
        $this->assertSame('  CODE WITH SPACES  ', $whitespace->code);
    }

    public function test_code_property_preserves_exact_value(): void
    {
        $mixedCase = new Debtor(['code' => 'MiXeD-CaSe-123']);
        $this->assertSame('MiXeD-CaSe-123', $mixedCase->code);

        $leadingZeros = new Debtor(['code' => '00012345']);
        $this->assertSame('00012345', $leadingZeros->code);

        $quoted = new Debtor(['code' => 'CODE"WITH\'QUOTES']);
        $this->assertSame('CODE"WITH\'QUOTES', $quoted->code);
        $this->assertSame('CODE"WITH\'QUOTES', $quoted->toArray()['code']);
    }
}
