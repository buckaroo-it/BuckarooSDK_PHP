<?php

namespace Buckaroo\PaymentMethods\BuckarooWallet;

use Buckaroo\PaymentMethods\BuckarooWallet\Models\DepositReservePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\ReleasePayload;
use Buckaroo\PaymentMethods\BuckarooWallet\Models\Wallet;
use Buckaroo\PaymentMethods\PaymentMethod;

class BuckarooWallet extends PaymentMethod
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

        return $this->dataRequest();
    }

    public function reserve()
    {
        $depositPayload = new DepositReservePayload($this->payload);

        $wallet = new Wallet($this->payload);

        $this->request->setPayload($depositPayload);

        $this->setServiceList('Reserve', $wallet);

        return $this->dataRequest();
    }





}