<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Logging\Observers;

use Buckaroo\Handlers\Logging\Observer;
use Buckaroo\Handlers\Logging\Observers\ErrorReporter;
use ReflectionClass;
use Tests\TestCase;

class ErrorReporterTest extends TestCase
{
    public function test_implements_observer_interface(): void
    {
        $errorReporter = new ErrorReporter();

        $this->assertInstanceOf(Observer::class, $errorReporter);
    }

    public function test_has_reportable_methods(): void
    {
        $errorReporter = new ErrorReporter();

        $reflection = new ReflectionClass($errorReporter);
        $property = $reflection->getProperty('reportables');
        $property->setAccessible(true);

        $reportables = $property->getValue($errorReporter);

        $this->assertContains('error', $reportables);
        $this->assertContains('critical', $reportables);
        $this->assertContains('emergency', $reportables);
    }

    public function test_handle_returns_self_for_error_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('error', 'Error message', ['key' => 'value']);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_critical_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('critical', 'Critical message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_emergency_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('emergency', 'Emergency message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_info_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('info', 'Info message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_warning_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('warning', 'Warning message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_debug_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('debug', 'Debug message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_notice_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('notice', 'Notice message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_returns_self_for_alert_level(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('alert', 'Alert message', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_accepts_empty_context(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter->handle('error', 'Error with empty context', []);

        $this->assertSame($errorReporter, $result);
    }

    public function test_handle_accepts_complex_context(): void
    {
        $errorReporter = new ErrorReporter();

        $context = [
            'transaction_id' => 'TX12345',
            'error_code' => 500,
            'stack_trace' => ['line1', 'line2', 'line3'],
            'metadata' => [
                'user_id' => 123,
                'timestamp' => '2024-01-01T00:00:00Z',
            ],
        ];

        $result = $errorReporter->handle('error', 'Complex error', $context);

        $this->assertSame($errorReporter, $result);
    }

    public function test_non_reportable_methods_are_not_in_list(): void
    {
        $errorReporter = new ErrorReporter();

        $reflection = new ReflectionClass($errorReporter);
        $property = $reflection->getProperty('reportables');
        $property->setAccessible(true);

        $reportables = $property->getValue($errorReporter);

        $this->assertNotContains('info', $reportables);
        $this->assertNotContains('warning', $reportables);
        $this->assertNotContains('debug', $reportables);
        $this->assertNotContains('notice', $reportables);
        $this->assertNotContains('alert', $reportables);
    }

    public function test_handle_is_chainable(): void
    {
        $errorReporter = new ErrorReporter();

        $result = $errorReporter
            ->handle('error', 'First error', [])
            ->handle('critical', 'Second error', [])
            ->handle('info', 'Info message', []);

        $this->assertSame($errorReporter, $result);
    }
}
