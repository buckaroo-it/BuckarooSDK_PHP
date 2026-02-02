<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Bancontact\Models;

use Buckaroo\PaymentMethods\Bancontact\Models\Authenticate;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    /** @test */
    public function it_sets_save_token_true(): void
    {
        $authenticate = new Authenticate(['saveToken' => true]);

        $this->assertTrue($authenticate->saveToken);
    }

    /** @test */
    public function it_sets_save_token_false(): void
    {
        $authenticate = new Authenticate(['saveToken' => false]);

        $this->assertFalse($authenticate->saveToken);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $authenticate = new Authenticate(['saveToken' => true]);

        $array = $authenticate->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['saveToken']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $authenticate = new Authenticate([]);

        $array = $authenticate->toArray();
        $this->assertIsArray($array);
    }
}
