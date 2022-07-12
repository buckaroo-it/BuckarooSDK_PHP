<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\DepositReservePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\ReleasePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\Wallet;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class BuckarooWallet extends PayablePaymentMethod
{
    protected string $paymentName = 'BuckarooWalletCollecting';

    public function createWallet()
    {
        $this->requiredConfigFields = ['currency'];

        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Create', $wallet);

        return $this->dataRequest();
    }

    public function updateWallet()
    {
        $wallet = new Wallet($this->payload);

        $this->setServiceList('Update', $wallet);

        return $this->dataRequest();
    }

    public function getInfo()
    {
        $wallet = new Wallet($this->payload);

        $this->setServiceList('GetInfo', $wallet);

        return $this->dataRequest();
    }

    public function release()
    {
        $relasePayload = new ReleasePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($relasePayload);

        $this->setServiceList('Release', $wallet);

        return $this->dataRequest();
    }

    public function deposit()
    {
        $depositPayload = new DepositReservePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($depositPayload);

        $this->setServiceList('Deposit', $wallet);

        return $this->postRequest();
    }

    public function reserve()
    {
        $depositPayload = new DepositReservePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($depositPayload);

        $this->setServiceList('Reserve', $wallet);

        return $this->postRequest();
    }

    public function withdrawal()
    {
        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Withdrawal', $wallet);

        return $this->postRequest();
    }

    public function cancel()
    {
        $wallet = new Wallet($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Cancel', $wallet);

        return $this->postRequest();
    }

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay(new Wallet($this->payload));
    }

}