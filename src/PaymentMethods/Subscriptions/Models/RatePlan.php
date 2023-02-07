<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\ServiceParameter;

class RatePlan extends ServiceParameter
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    protected string $ratePlanGuid;
    /**
     * @var string
     */
    protected string $ratePlanCode;
    /**
     * @var string
     */
    protected string $startDate;
    /**
     * @var string
     */
    protected string $endDate;
    /**
     * @var string
     */
    protected string $ratePlanName;
    /**
     * @var string
     */
    protected string $ratePlanDescription;
    /**
     * @var string
     */
    protected string $currency;
    /**
     * @var int
     */
    protected int $billingTiming;
    /**
     * @var bool
     */
    protected bool $automaticTerm;
    /**
     * @var string
     */
    protected string $billingInterval;
    /**
     * @var int
     */
    protected int $customNumberOfDays;
    /**
     * @var int
     */
    protected int $termStartDay;
    /**
     * @var string
     */
    protected string $termStartWeek;
    /**
     * @var string
     */
    protected string $termStartMonth;
    /**
     * @var int
     */
    protected int $trialPeriodDays;
    /**
     * @var int
     */
    protected int $trialPeriodMonths;
    /**
     * @var bool
     */
    protected bool $inheritPaymentMethod;
}
