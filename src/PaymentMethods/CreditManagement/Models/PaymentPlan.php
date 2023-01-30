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

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;

class PaymentPlan extends ServiceParameter
{
    protected string $includedInvoiceKey;
    protected string $dossierNumber;
    protected int $installmentCount;
    protected float $installmentAmount;
    protected float $initialAmount;
    protected string $startDate;
    protected string $interval;
    protected float $paymentPlanCostAmount;
    protected float $paymentPlanCostAmountVat;
    protected string $recipientEmail;
}
