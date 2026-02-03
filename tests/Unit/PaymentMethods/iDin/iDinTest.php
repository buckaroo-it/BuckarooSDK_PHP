<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDin;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class iDinTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_identify(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idin')->identify([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_verify(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idin')->verify([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /** @test */
    public function it_can_login(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('idin')->login([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
