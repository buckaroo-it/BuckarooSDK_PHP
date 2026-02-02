<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;
use PHPUnit\Framework\TestCase;

class CreditManagementInstallmentIntervalTest extends TestCase
{
    public function test_day_constant(): void
    {
        $this->assertSame('Day', CreditManagementInstallmentInterval::DAY);
    }

    public function test_two_days_constant(): void
    {
        $this->assertSame('TwoDays', CreditManagementInstallmentInterval::TWODAYS);
    }

    public function test_week_constant(): void
    {
        $this->assertSame('Week', CreditManagementInstallmentInterval::WEEK);
    }

    public function test_two_weeks_constant(): void
    {
        $this->assertSame('TwoWeeks', CreditManagementInstallmentInterval::TWOWEEKS);
    }

    public function test_half_month_constant(): void
    {
        $this->assertSame('HalfMonth', CreditManagementInstallmentInterval::HALFMONTH);
    }

    public function test_month_constant(): void
    {
        $this->assertSame('Month', CreditManagementInstallmentInterval::MONTH);
    }

    public function test_two_months_constant(): void
    {
        $this->assertSame('TwoMonths', CreditManagementInstallmentInterval::TWOMONTHS);
    }

    public function test_quarter_year_constant(): void
    {
        $this->assertSame('QuarterYear', CreditManagementInstallmentInterval::QUARTERYEAR);
    }

    public function test_half_year_constant(): void
    {
        $this->assertSame('HalfYear', CreditManagementInstallmentInterval::HALFYEAR);
    }

    public function test_year_constant(): void
    {
        $this->assertSame('Year', CreditManagementInstallmentInterval::YEAR);
    }

    public function test_all_constants_are_strings(): void
    {
        $this->assertIsString(CreditManagementInstallmentInterval::DAY);
        $this->assertIsString(CreditManagementInstallmentInterval::TWODAYS);
        $this->assertIsString(CreditManagementInstallmentInterval::WEEK);
        $this->assertIsString(CreditManagementInstallmentInterval::TWOWEEKS);
        $this->assertIsString(CreditManagementInstallmentInterval::HALFMONTH);
        $this->assertIsString(CreditManagementInstallmentInterval::MONTH);
        $this->assertIsString(CreditManagementInstallmentInterval::TWOMONTHS);
        $this->assertIsString(CreditManagementInstallmentInterval::QUARTERYEAR);
        $this->assertIsString(CreditManagementInstallmentInterval::HALFYEAR);
        $this->assertIsString(CreditManagementInstallmentInterval::YEAR);
    }

    public function test_all_values_are_unique(): void
    {
        $values = [
            CreditManagementInstallmentInterval::DAY,
            CreditManagementInstallmentInterval::TWODAYS,
            CreditManagementInstallmentInterval::WEEK,
            CreditManagementInstallmentInterval::TWOWEEKS,
            CreditManagementInstallmentInterval::HALFMONTH,
            CreditManagementInstallmentInterval::MONTH,
            CreditManagementInstallmentInterval::TWOMONTHS,
            CreditManagementInstallmentInterval::QUARTERYEAR,
            CreditManagementInstallmentInterval::HALFYEAR,
            CreditManagementInstallmentInterval::YEAR,
        ];

        $this->assertCount(10, array_unique($values));
    }
}
