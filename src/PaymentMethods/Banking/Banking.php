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

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Banking;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Banking\Models\InstantPaymentOrder;
use Buckaroo\PaymentMethods\Banking\Models\PaymentOrder;
use Buckaroo\PaymentMethods\Banking\Models\PaymentOrderPayload;
use Buckaroo\PaymentMethods\Banking\Service\ParameterKeys\PaymentOrderAdapter;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Banking extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'Banking';

    /**
     * @return TransactionResponse
     */
    public function paymentOrder(?Model $model = null): TransactionResponse
    {
        $paymentOrderPayload = new PaymentOrderPayload($this->payload);

        $this->request->setPayload($paymentOrderPayload);

        $this->setServiceList('PaymentOrder', $model ?? new PaymentOrderAdapter(new PaymentOrder($this->payload)));

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function instantPaymentOrder(?Model $model = null): TransactionResponse
    {
        $paymentOrderPayload = new PaymentOrderPayload($this->payload);

        $this->request->setPayload($paymentOrderPayload);

        $this->setServiceList('InstantPaymentOrder', $model ?? new PaymentOrderAdapter(new InstantPaymentOrder($this->payload)));

        return $this->postRequest();
    }
}
