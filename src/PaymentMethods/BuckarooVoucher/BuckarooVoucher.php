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

namespace Buckaroo\PaymentMethods\BuckarooVoucher;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Create;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Deactivate;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\GetBalance;
use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class BuckarooVoucher extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'buckaroovoucher';

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        $this->setPayPayload();

        $pay = new Pay($this->payload);

        return parent::pay($model ?? $pay);
    }


    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function payRemainder(?Model $model = null): TransactionResponse
    {
        $this->setPayPayload();

        $pay = new Pay($this->payload);

        return parent::payRemainder($model ?? $pay);
    }

    /**
     * @return TransactionResponse
     */
    public function getBalance(): TransactionResponse
    {
        $data = new GetBalance($this->payload);

        $this->setPayPayload();

        $this->setServiceList('GetBalance', $data);

        return $this->dataRequest();
    }
    /**
     * @return TransactionResponse
     */
    public function create(): TransactionResponse
    {
        $data = new Create($this->payload);

        $this->setPayPayload();

        $this->setServiceList('CreateApplication', $data);

        return $this->dataRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function deactivate(): TransactionResponse
    {
        $data = new Deactivate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('DeactivateVoucher', $data);

        return $this->dataRequest();
    }
}
