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

namespace Buckaroo\PaymentMethods\BuckarooWallet;

use Buckaroo\Models\Model;
use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\DepositReservePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\ReleasePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\Wallet;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class BuckarooWallet extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'BuckarooWalletCollecting';

    /**
     * @return BuckarooWallet|mixed
     */
    public function createWallet()
    {
        $this->payModel = DataRequestPayload::class;

        $this->requiredConfigFields = ['currency'];

        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Create', $wallet);

        return $this->dataRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function updateWallet()
    {
        $this->payModel = DataRequestPayload::class;

        $wallet = new Wallet($this->payload);

        $this->setServiceList('Update', $wallet);

        return $this->dataRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function getInfo()
    {
        $this->payModel = DataRequestPayload::class;

        $wallet = new Wallet($this->payload);

        $this->setServiceList('GetInfo', $wallet);

        return $this->dataRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function release()
    {
        $this->payModel = DataRequestPayload::class;

        $relasePayload = new ReleasePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($relasePayload);

        $this->setServiceList('Release', $wallet);

        return $this->dataRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function deposit()
    {
        $depositPayload = new DepositReservePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($depositPayload);

        $this->setServiceList('Deposit', $wallet);

        return $this->postRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function reserve()
    {
        $depositPayload = new DepositReservePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($depositPayload);

        $this->setServiceList('Reserve', $wallet);

        return $this->postRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function withdrawal()
    {
        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Withdrawal', $wallet);

        return $this->postRequest();
    }

    /**
     * @return BuckarooWallet|mixed
     */
    public function cancel()
    {
        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Cancel', $wallet);

        return $this->postRequest();
    }

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Wallet($this->payload));
    }
}
