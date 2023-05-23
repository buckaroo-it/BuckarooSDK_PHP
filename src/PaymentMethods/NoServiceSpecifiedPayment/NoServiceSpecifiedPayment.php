<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl, so we can send you a copy immediately.
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

namespace Buckaroo\PaymentMethods\NoServiceSpecifiedPayment;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethod;

/**
 *
 */
class NoServiceSpecifiedPayment extends PayablePaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'noservice';

    /**
     * @param string|null $action
     * @param Model|null $model
     * @return PaymentMethod
     */
    protected function setServiceList(?string $action, ?Model $model = null): PaymentMethod
    {
        return $this;
    }
}
