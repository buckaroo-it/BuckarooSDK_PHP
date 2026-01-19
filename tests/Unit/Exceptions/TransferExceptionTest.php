<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Exceptions\TransferException;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Exception;
use PHPUnit\Framework\TestCase;

class TransferExceptionTest extends TestCase
{
    public function test_it_extends_buckaroo_exception(): void
    {
        $exception = new TransferException(null, 'Test message');

        $this->assertInstanceOf(BuckarooException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function test_it_prepends_transfer_exception_prefix(): void
    {
        $exception = new TransferException(null, 'Transfer failed');

        $this->assertStringStartsWith('Buckaroo TransferException', $exception->getMessage());
        $this->assertSame('Buckaroo TransferException Transfer failed', $exception->getMessage());
    }

    public function test_it_creates_exception_with_code(): void
    {
        $exception = new TransferException(null, 'Transfer error', 503);

        $this->assertSame(503, $exception->getCode());
    }

    public function test_it_creates_exception_with_previous_exception(): void
    {
        $guzzleException = new Exception('Guzzle HTTP error');
        $exception = new TransferException(null, 'Transfer failed', 0, $guzzleException);

        $this->assertSame($guzzleException, $exception->getPrevious());
    }

    public function test_it_is_catchable_as_buckaroo_exception(): void
    {
        $caught = false;

        try {
            throw new TransferException(null, 'Network error');
        } catch (BuckarooException $e) {
            $caught = true;
            $this->assertInstanceOf(TransferException::class, $e);
        }

        $this->assertTrue($caught);
    }

    public function test_it_logs_when_logger_provided(): void
    {
        $logger = new DefaultLogger();

        // Should not throw errors when logging
        $exception = new TransferException($logger, 'Logged transfer error');

        $this->assertInstanceOf(TransferException::class, $exception);
    }

    public function test_it_can_be_thrown_and_caught(): void
    {
        $this->expectException(TransferException::class);
        $this->expectExceptionMessage('Buckaroo TransferException Connection timeout');

        throw new TransferException(null, 'Connection timeout');
    }

    public function test_message_format_differs_from_base_exception(): void
    {
        $buckarooException = new BuckarooException(null, 'Test');
        $transferException = new TransferException(null, 'Test');

        $this->assertSame('Buckaroo SDKExeption: Test', $buckarooException->getMessage());
        $this->assertSame('Buckaroo TransferException Test', $transferException->getMessage());
    }
}
