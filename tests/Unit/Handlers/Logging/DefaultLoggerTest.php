<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Logging;

use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Observer;
use Tests\TestCase;

class DefaultLoggerTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['BPE_DEBUG'], $_ENV['BPE_LOG'], $_ENV['BPE_REPORT_ERROR']);
        parent::tearDown();
    }

    public function test_attaches_single_observer(): void
    {
        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();

        $result = $logger->attach($observer);

        $this->assertSame($logger, $result);
    }

    public function test_attaches_multiple_observers_as_array(): void
    {
        $logger = new DefaultLogger();
        $observer1 = $this->createMockObserver();
        $observer2 = $this->createMockObserver();

        $result = $logger->attach([$observer1, $observer2]);

        $this->assertSame($logger, $result);
    }

    public function test_detaches_observer(): void
    {
        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();

        $logger->attach($observer);
        $result = $logger->detach($observer);

        $this->assertSame($logger, $result);
    }

    public function test_notifies_observers_for_all_psr3_log_levels(): void
    {
        $levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info'];

        foreach ($levels as $level) {
            $logger = new DefaultLogger();
            $observer = $this->createMockObserver();

            $observer->expects($this->once())
                ->method('handle')
                ->with($level, "Test {$level} message", ['key' => 'value']);

            $logger->attach($observer);
            $logger->$level("Test {$level} message", ['key' => 'value']);
        }
    }

    public function test_log_method_uses_log_level_in_notification(): void
    {
        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();

        $observer->expects($this->once())
            ->method('handle')
            ->with('log', 'Generic log message', ['context' => 'data']);

        $logger->attach($observer);
        $logger->log('info', 'Generic log message', ['context' => 'data']);
    }

    public function test_debug_logs_when_bpe_debug_enabled(): void
    {
        $_ENV['BPE_DEBUG'] = true;

        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();
        $observer->expects($this->once())
            ->method('handle')
            ->with('debug', 'Debug message', []);

        $logger->attach($observer);
        $logger->debug('Debug message');
    }

    public function test_debug_does_not_log_when_bpe_debug_disabled(): void
    {
        unset($_ENV['BPE_DEBUG']);

        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();
        $observer->expects($this->never())
            ->method('handle');

        $logger->attach($observer);
        $logger->debug('Debug message');
    }

    public function test_notifies_multiple_observers(): void
    {
        $logger = new DefaultLogger();
        $observer1 = $this->createMockObserver();
        $observer2 = $this->createMockObserver();

        $observer1->expects($this->once())
            ->method('handle')
            ->with('info', 'Test message', []);
        $observer2->expects($this->once())
            ->method('handle')
            ->with('info', 'Test message', []);

        $logger->attach($observer1);
        $logger->attach($observer2);
        $logger->info('Test message');
    }

    public function test_detached_observer_does_not_receive_notifications(): void
    {
        $logger = new DefaultLogger();
        $observer = $this->createMockObserver();

        $observer->expects($this->never())
            ->method('handle');

        $logger->attach($observer);
        $logger->detach($observer);
        $logger->info('Test message');
    }

    public function test_ignores_non_observer_attachments(): void
    {
        $logger = new DefaultLogger();
        $notAnObserver = new \stdClass();

        $result = $logger->attach($notAnObserver);

        $this->assertSame($logger, $result);
    }

    /**
     * Creates a mock Observer for testing notification behavior.
     *
     * Note: While we generally avoid PHPUnit mocking, mocking interfaces
     * (contracts with no implementation) is acceptable when testing
     * observer/listener patterns.
     */
    private function createMockObserver(): Observer
    {
        return $this->createMock(Observer::class);
    }
}
