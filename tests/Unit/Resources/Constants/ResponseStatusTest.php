<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\ResponseStatus;
use PHPUnit\Framework\TestCase;

class ResponseStatusTest extends TestCase
{
    public function test_success_status_code(): void
    {
        $this->assertSame('190', ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS);
    }

    public function test_failed_status_code(): void
    {
        $this->assertSame('490', ResponseStatus::BUCKAROO_STATUSCODE_FAILED);
    }

    public function test_validation_failure_status_code(): void
    {
        $this->assertSame('491', ResponseStatus::BUCKAROO_STATUSCODE_VALIDATION_FAILURE);
    }

    public function test_technical_error_status_code(): void
    {
        $this->assertSame('492', ResponseStatus::BUCKAROO_STATUSCODE_TECHNICAL_ERROR);
    }

    public function test_rejected_status_code(): void
    {
        $this->assertSame('690', ResponseStatus::BUCKAROO_STATUSCODE_REJECTED);
    }

    public function test_waiting_on_user_input_status_code(): void
    {
        $this->assertSame('790', ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT);
    }

    public function test_pending_processing_status_code(): void
    {
        $this->assertSame('791', ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING);
    }

    public function test_waiting_on_consumer_status_code(): void
    {
        $this->assertSame('792', ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER);
    }

    public function test_payment_on_hold_status_code(): void
    {
        $this->assertSame('793', ResponseStatus::BUCKAROO_STATUSCODE_PAYMENT_ON_HOLD);
    }

    public function test_pending_approval_status_code(): void
    {
        $this->assertSame('794', ResponseStatus::BUCKAROO_STATUSCODE_PENDING_APPROVAL);
    }

    public function test_cancelled_by_user_status_code(): void
    {
        $this->assertSame('890', ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER);
    }

    public function test_cancelled_by_merchant_status_code(): void
    {
        $this->assertSame('891', ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT);
    }

    public function test_authorize_type_cancel(): void
    {
        $this->assertSame('I014', ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_CANCEL);
    }

    public function test_authorize_type_accept(): void
    {
        $this->assertSame('I013', ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_ACCEPT);
    }

    public function test_authorize_type_group_transaction(): void
    {
        $this->assertSame('I150', ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_GROUP_TRANSACTION);
    }

    public function test_status_codes_are_string_type(): void
    {
        $this->assertIsString(ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS);
        $this->assertIsString(ResponseStatus::BUCKAROO_STATUSCODE_FAILED);
        $this->assertIsString(ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING);
    }
}
