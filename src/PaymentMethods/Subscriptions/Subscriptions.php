<?php

namespace Buckaroo\PaymentMethods\Subscriptions;

use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Subscriptions\Models\CombinedPayload;
use Buckaroo\PaymentMethods\Subscriptions\Models\ResumeSubscription;
use Buckaroo\PaymentMethods\Subscriptions\Models\Subscription;

class Subscriptions extends PaymentMethod implements Combinable
{
    protected string $paymentName = 'Subscriptions';

    public function create()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('CreateSubscription', $subscription);

        return $this->dataRequest();
    }

    public function createCombined()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('CreateCombinedSubscription', $subscription);

        return $this->dataRequest();
    }

    public function update()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('UpdateSubscription', $subscription);

        return $this->dataRequest();
    }

    public function updateCombined()
    {
        $subscription = new Subscription($this->payload);

        $payPayload = new CombinedPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('UpdateCombinedSubscription', $subscription);

        return $this->dataRequest();
    }

    public function stop()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('StopSubscription', $subscription);

        return $this->dataRequest();
    }

    public function info()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('SubscriptionInfo', $subscription);

        return $this->dataRequest();
    }

    public function deletePaymentConfig()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('DeletePaymentConfiguration', $subscription);

        return $this->dataRequest();
    }

    public function pause()
    {
        $subscription = new ResumeSubscription($this->payload);

        $this->setServiceList('PauseSubscription', $subscription);

        return $this->dataRequest();
    }

    public function resume()
    {
        $subscription = new ResumeSubscription($this->payload);

        $this->setServiceList('ResumeSubscription', $subscription);

        return $this->dataRequest();
    }
}