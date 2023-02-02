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

class RatePlanCharge extends ServiceParameter
{
    protected string $ratePlanChargeCode;

    protected string $ratePlanChargeName;
    protected string $rateplanChargeProductId;
    protected string $rateplanChargeDescription;
    protected string $unitOfMeasure;
    protected float $baseNumberOfUnits;
    protected string $partialBilling;
    protected float $pricePerUnit;
    protected bool $priceIncludesVat;
    protected float $vatPercentage;
    protected string $b2B;
}