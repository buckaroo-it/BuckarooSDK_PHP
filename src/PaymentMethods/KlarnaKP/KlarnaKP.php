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

namespace Buckaroo\PaymentMethods\KlarnaKP;

use Buckaroo\Models\Model;
use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\PaymentMethods\KlarnaKP\Models\Payload;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class KlarnaKP extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'klarnakp';

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Payload($this->payload));
    }

    /**
     * @return TransactionResponse
     */
    public function reserve(): TransactionResponse
    {
        $this->payModel = DataRequestPayload::class;

        $reserve = new Payload($this->payload);

        $this->setServiceList('Reserve', $reserve);

        $this->setPayPayload();

        return $this->dataRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function cancelReserve(): TransactionResponse
    {
        $this->payModel = DataRequestPayload::class;

        $cancel = new Payload($this->payload);

        $this->setServiceList('CancelReservation', $cancel);

        $this->setPayPayload();

        return $this->dataRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function updateReserve(): TransactionResponse
    {
        $this->payModel = DataRequestPayload::class;

        $update = new Payload($this->payload);

        $this->setServiceList('UpdateReservation', $update);

        $this->setPayPayload();

        return $this->dataRequest();
    }
}
