<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\ClickToPay;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class ClickToPayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_sends_identifier_and_transient_token_as_service_parameters(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/Transaction*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('clicktopay')->pay([
            'amountDebit'    => 30.00,
            'invoice'        => 'INV-CTP-001',
            'identifier'     => 'CTP-IDENT-123',
            'transientToken' => 'tok_transient_abc',
        ]);

        $this->assertTrue($response->isSuccess());

        $body = json_decode($this->mockBuckaroo->getLastRequestBody() ?? '', true);
        $parameters = $this->collectServiceParameters($body);

        $this->assertSame('CTP-IDENT-123', $parameters['Identifier'] ?? null);
        $this->assertSame('tok_transient_abc', $parameters['TransientToken'] ?? null);
    }

    /**
     * Recursively collect Buckaroo service parameters ({"Name": ..., "Value": ...})
     * from the request body, regardless of the surrounding structure.
     *
     * @param mixed $node
     * @return array<string, mixed>
     */
    private function collectServiceParameters($node): array
    {
        $found = [];

        if (!is_array($node)) {
            return $found;
        }

        if (isset($node['Name']) && array_key_exists('Value', $node)) {
            $found[$node['Name']] = $node['Value'];
        }

        foreach ($node as $child) {
            if (is_array($child)) {
                $found += $this->collectServiceParameters($child);
            }
        }

        return $found;
    }
}
