<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDealQR;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class iDealQRTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_can_generate_qr_code(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', TestHelpers::successResponse()),
        ]);

        $response = $this->buckaroo->method('ideal_qr')->generate([
            'amountDebit' => 10.00,
            'invoice' => 'IDEALQR-GEN-001',
            'description' => 'Test QR Code',
            'imageSize' => 200,
            'purchaseId' => 'PURCHASE-001',
            'minAmount' => 1.00,
            'maxAmount' => 100.00,
            'isOneOff' => false,
            'expiration' => date('Y-m-d', strtotime('+1 day')),
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
