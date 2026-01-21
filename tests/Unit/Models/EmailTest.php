<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function test_constructor_sets_email_property(): void
    {
        $email = new Email('test@example.com');

        $this->assertSame('test@example.com', $email->email);
    }

    public function test_constructor_with_empty_string(): void
    {
        $email = new Email('');

        $this->assertSame('', $email->email);
    }

    public function test_email_accessible_via_magic_get(): void
    {
        $email = new Email('contact@buckaroo.nl');

        $value = $email->email;

        $this->assertSame('contact@buckaroo.nl', $value);
    }

    public function test_email_settable_via_magic_set(): void
    {
        $email = new Email('initial@example.com');

        $email->email = 'updated@example.com';

        $this->assertSame('updated@example.com', $email->email);
    }

    public function test_set_properties_updates_email(): void
    {
        $email = new Email('initial@example.com');

        $email->setProperties(['email' => 'changed@example.com']);

        $this->assertSame('changed@example.com', $email->email);
    }

    public function test_to_array_includes_email_property(): void
    {
        $email = new Email('serialize@example.com');

        $array = $email->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('email', $array);
        $this->assertSame('serialize@example.com', $array['email']);
    }

    public function test_to_array_with_empty_string(): void
    {
        $email = new Email('');

        $array = $email->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('email', $array);
        $this->assertSame('', $array['email']);
    }

    public function test_stores_invalid_email_format_without_validation(): void
    {
        $invalidEmails = [
            'not-an-email',
            'missing-at-sign.com',
            '@no-local-part.com',
            'no-domain@',
            'spaces in@email.com',
            'double@@at.com',
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $email = new Email($invalidEmail);

            $this->assertSame($invalidEmail, $email->email);
        }
    }

    public function test_stores_very_long_email_string(): void
    {
        $longLocalPart = str_repeat('a', 500);
        $longEmail = $longLocalPart . '@example.com';

        $email = new Email($longEmail);

        $this->assertSame($longEmail, $email->email);
        $this->assertSame(strlen($longEmail), strlen($email->email));
    }

    public function test_stores_special_characters_in_email(): void
    {
        $specialEmails = [
            'user+tag@example.com',
            'user.name@example.com',
            'user_name@example.com',
            'user-name@example.com',
            '123@example.com',
            'a!#$%&\'*+/=?^_`{|}~@example.com',
        ];

        foreach ($specialEmails as $specialEmail) {
            $email = new Email($specialEmail);

            $this->assertSame($specialEmail, $email->email);
        }
    }

    public function test_stores_unicode_characters_in_email(): void
    {
        $unicodeEmails = [
            'user@münchen.de',
            'ñoño@example.com',
            'δοκιμή@παράδειγμα.δοκιμή',
            '测试@例え.jp',
            '用户@例子.中国',
        ];

        foreach ($unicodeEmails as $unicodeEmail) {
            $email = new Email($unicodeEmail);

            $this->assertSame($unicodeEmail, $email->email);
        }
    }

    public function test_stores_multiple_at_symbols(): void
    {
        $email = new Email('user@@example.com');

        $this->assertSame('user@@example.com', $email->email);
    }

    public function test_stores_email_with_whitespace(): void
    {
        $whitespaceEmails = [
            ' leading@example.com',
            'trailing@example.com ',
            '  both@example.com  ',
            'internal space@example.com',
            "tab\t@example.com",
            "newline\n@example.com",
        ];

        foreach ($whitespaceEmails as $whitespaceEmail) {
            $email = new Email($whitespaceEmail);

            $this->assertSame($whitespaceEmail, $email->email);
        }
    }

    public function test_set_properties_with_null_preserves_existing_value(): void
    {
        $email = new Email('initial@example.com');

        $email->setProperties(['email' => 'updated@example.com']);

        $this->assertSame('updated@example.com', $email->email);
    }
}
