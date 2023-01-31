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

namespace Buckaroo\PaymentMethods\Emandates\Models;

use Buckaroo\Models\ServiceParameter;

class Mandate extends ServiceParameter
{
    protected string $debtorbankid;
    protected string $debtorreference;
    protected float $sequencetype;
    protected string $purchaseid;
    protected string $mandateid;
    protected string $language;
    protected string $emandatereason;
    protected float $maxamount;
    protected string $originalMandateId;
}
