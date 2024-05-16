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

namespace Buckaroo\PaymentMethods\Billink;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Billink\Models\Capture;
use Buckaroo\PaymentMethods\Billink\Models\Pay;
use Buckaroo\PaymentMethods\Billink\Models\Refund;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Billink extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'Billink';

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
    public function capture(): TransactionResponse
    {
        $capture = new Capture($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Capture', $capture);

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function cancelAuthorize(): TransactionResponse
    {
        $cancel = new Refund($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CancelAuthorize', $cancel);

        return $this->postRequest();
    }
}
