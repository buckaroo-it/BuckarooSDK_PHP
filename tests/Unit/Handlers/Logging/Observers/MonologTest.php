<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Logging\Observers;

use Buckaroo\Handlers\Logging\Observer;
use Buckaroo\Handlers\Logging\Observers\Monolog;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use ReflectionClass;
use Tests\TestCase;

class MonologTest extends TestCase
{
    public function test_implements_observer_interface(): void
    {
        $monolog = new Monolog();

        $this->assertInstanceOf(Observer::class, $monolog);
    }

    public function test_creates_logger_with_buckaroo_name(): void
    {
        $monolog = new Monolog();

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);

        $logger = $property->getValue($monolog);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('Buckaroo log', $logger->getName());
    }

    public function test_handle_method_logs_info_level(): void
    {
        $monolog = new Monolog();

        // Replace the internal logger with a test handler
        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('info', 'Test info message', ['key' => 'value']);

        $this->assertTrue($testHandler->hasInfoRecords());
        $this->assertTrue($testHandler->hasInfo('Test info message'));
    }

    public function test_handle_method_logs_error_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('error', 'Test error message', ['error' => 'details']);

        $this->assertTrue($testHandler->hasErrorRecords());
        $this->assertTrue($testHandler->hasError('Test error message'));
    }

    public function test_handle_method_logs_warning_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('warning', 'Test warning message', []);

        $this->assertTrue($testHandler->hasWarningRecords());
        $this->assertTrue($testHandler->hasWarning('Test warning message'));
    }

    public function test_handle_method_logs_debug_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('debug', 'Test debug message', []);

        $this->assertTrue($testHandler->hasDebugRecords());
        $this->assertTrue($testHandler->hasDebug('Test debug message'));
    }

    public function test_handle_method_passes_context_to_logger(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $context = ['transaction_id' => '12345', 'amount' => 10.50];
        $monolog->handle('info', 'Transaction processed', $context);

        $records = $testHandler->getRecords();
        $this->assertCount(1, $records);
        $this->assertSame($context, $records[0]['context']);
    }

    public function test_handle_method_logs_critical_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('critical', 'Critical error occurred', []);

        $this->assertTrue($testHandler->hasCriticalRecords());
        $this->assertTrue($testHandler->hasCritical('Critical error occurred'));
    }

    public function test_handle_method_logs_alert_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('alert', 'Alert message', []);

        $this->assertTrue($testHandler->hasAlertRecords());
        $this->assertTrue($testHandler->hasAlert('Alert message'));
    }

    public function test_handle_method_logs_emergency_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('emergency', 'Emergency message', []);

        $this->assertTrue($testHandler->hasEmergencyRecords());
        $this->assertTrue($testHandler->hasEmergency('Emergency message'));
    }

    public function test_handle_method_logs_notice_level(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('notice', 'Notice message', []);

        $this->assertTrue($testHandler->hasNoticeRecords());
        $this->assertTrue($testHandler->hasNotice('Notice message'));
    }

    public function test_handle_with_empty_context(): void
    {
        $monolog = new Monolog();

        $testHandler = new TestHandler();
        $testLogger = new Logger('test', [$testHandler]);

        $reflection = new ReflectionClass($monolog);
        $property = $reflection->getProperty('log');
        $property->setAccessible(true);
        $property->setValue($monolog, $testLogger);

        $monolog->handle('info', 'Message without context', []);

        $records = $testHandler->getRecords();
        $this->assertCount(1, $records);
        $this->assertSame([], $records[0]['context']);
    }
}
