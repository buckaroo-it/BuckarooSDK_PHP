<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditCard\Models;

use Buckaroo\PaymentMethods\CreditCard\Models\SessionData;
use Tests\TestCase;

class SessionDataTest extends TestCase
{
    /** @test */
    public function it_sets_session_id_via_constructor(): void
    {
        $model = new SessionData(['sessionId' => 'session-123-abc']);

        $this->assertSame('session-123-abc', $model->sessionId);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $model = new SessionData(['sessionId' => 'session-789-ghi']);

        $array = $model->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('sessionId', $array);
        $this->assertSame('session-789-ghi', $array['sessionId']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $model = new SessionData([]);

        $array = $model->toArray();
        $this->assertIsArray($array);
    }
}
