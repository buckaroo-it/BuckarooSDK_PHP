<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Observer;
use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;

class BuckarooExceptionTest extends TestCase
{
    public function test_it_extends_base_exception(): void
    {
        $exception = new BuckarooException(null, 'Test message');

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(Throwable::class, $exception);
    }

    public function test_it_creates_exception_with_message(): void
    {
        $exception = new BuckarooException(null, 'Test error message');

        $this->assertStringContainsString('Test error message', $exception->getMessage());
    }

    public function test_it_prepends_sdk_exception_prefix_to_message(): void
    {
        $exception = new BuckarooException(null, 'Original message');

        $this->assertStringStartsWith('Buckaroo SDKExeption:', $exception->getMessage());
        $this->assertSame('Buckaroo SDKExeption: Original message', $exception->getMessage());
    }

    public function test_it_creates_exception_with_code(): void
    {
        $exception = new BuckarooException(null, 'Test message', 500);

        $this->assertSame(500, $exception->getCode());
    }

    public function test_it_creates_exception_with_previous_exception(): void
    {
        $previous = new Exception('Previous exception');
        $exception = new BuckarooException(null, 'Current message', 0, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function test_it_is_throwable(): void
    {
        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Buckaroo SDKExeption: Thrown exception');

        throw new BuckarooException(null, 'Thrown exception');
    }

    public function test_it_can_be_caught(): void
    {
        $caught = false;

        try {
            throw new BuckarooException(null, 'Caught exception');
        } catch (BuckarooException $e) {
            $caught = true;
            $this->assertStringContainsString('Caught exception', $e->getMessage());
        }

        $this->assertTrue($caught);
    }

    public function test_it_logs_error_when_logger_provided(): void
    {
        $loggedMessage = null;

        $observer = new class($loggedMessage) implements Observer {
            private $loggedMessage;

            public function __construct(&$loggedMessage)
            {
                $this->loggedMessage = &$loggedMessage;
            }

            public function handle(string $method, string $message, array $context = []): void
            {
                if ($method === 'error') {
                    $this->loggedMessage = $message;
                }
            }
        };

        $logger = new DefaultLogger();
        $logger->attach($observer);

        new BuckarooException($logger, 'Logged error message');

        $this->assertSame('Buckaroo SDKExeption: Logged error message', $loggedMessage);
    }

    public function test_it_does_not_log_when_logger_is_null(): void
    {
        // Should not throw any errors when logger is null
        $exception = new BuckarooException(null, 'No logger message');

        $this->assertInstanceOf(BuckarooException::class, $exception);
    }

    public function test_it_handles_empty_message(): void
    {
        $exception = new BuckarooException(null, '');

        $this->assertSame('Buckaroo SDKExeption: ', $exception->getMessage());
    }

    public function test_it_handles_special_characters_in_message(): void
    {
        $message = 'Error with special chars: "quoted" & <html> and unicode: é';

        $exception = new BuckarooException(null, $message);

        $this->assertStringContainsString($message, $exception->getMessage());
    }

    public function test_exception_chaining(): void
    {
        $root = new Exception('Root cause');
        $middle = new BuckarooException(null, 'Middle exception', 0, $root);
        $top = new BuckarooException(null, 'Top exception', 0, $middle);

        $this->assertSame($middle, $top->getPrevious());
        $this->assertSame($root, $top->getPrevious()->getPrevious());
    }
}
