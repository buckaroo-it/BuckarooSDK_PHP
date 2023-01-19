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

namespace Buckaroo\PaymentMethods;

use Buckaroo\Models\Model;
use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\Models\Payload\RefundPayload;

abstract class PayablePaymentMethod extends PaymentMethod
{
    /**
     * @var string
     */
    protected string $payModel = PayPayload::class;
    /**
     * @var string
     */
    protected string $refundModel = RefundPayload::class;

    /**
     * @param Model|null $model
     * @return PayablePaymentMethod|mixed
     */
    public function pay(?Model $model = null)
    {
        $this->setPayPayload();

        $this->setServiceList('Pay', $model);

        //TODO
        //Create validator class that validates specific request
        //$request->validate();

        return $this->postRequest();
    }

    /**
     * @param Model|null $model
     * @return PayablePaymentMethod|mixed
     */
    public function payRemainder(?Model $model = null)
    {
        $this->setPayPayload();

        $this->setServiceList('PayRemainder', $model);

        return $this->postRequest();
    }

    /**
     * @param Model|null $model
     * @return PayablePaymentMethod|mixed
     */
    public function refund(?Model $model = null)
    {
        $this->setRefundPayload();

        $this->setServiceList('Refund', $model);

        return $this->postRequest();
    }

    /**
     * @return $this
     */
    protected function setPayPayload()
    {
        $payPayload = new $this->payModel($this->payload);

        $this->request->setPayload($payPayload);

        return $this;
    }

    /**
     * @return $this
     */
    protected function setRefundPayload()
    {
        $refundPayload = new $this->refundModel($this->payload);

        $this->request->setPayload($refundPayload);

        return $this;
    }
}
