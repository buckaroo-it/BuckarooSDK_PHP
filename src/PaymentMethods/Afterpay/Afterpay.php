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

namespace Buckaroo\PaymentMethods\Afterpay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Afterpay\Models\Pay;
use Buckaroo\PaymentMethods\Afterpay\Models\Refund;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Afterpay extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'afterpay';
    /**
     * @var int
     */
    protected int $serviceVersion = 1;

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    /**
     * @return TransactionResponse
     */
    public function authorize(): TransactionResponse
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Authorize', $pay);

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function cancelAuthorize(): TransactionResponse
    {
        $this->setRefundPayload();

        $this->setServiceList('CancelAuthorize');

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function capture(): TransactionResponse
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Capture', $pay);

        return $this->postRequest();
    }

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function refund(?Model $model = null): TransactionResponse
    {
        return parent::refund($model ?? new Refund($this->payload));
    }
}
