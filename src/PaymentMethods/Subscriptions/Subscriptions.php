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

namespace Buckaroo\PaymentMethods\Subscriptions;

use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Subscriptions\Models\CombinedPayload;
use Buckaroo\PaymentMethods\Subscriptions\Models\ResumeSubscription;
use Buckaroo\PaymentMethods\Subscriptions\Models\Subscription;
use function Ramsey\Uuid\v1;

class Subscriptions extends PaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'Subscriptions';

    /**
     * @return Subscriptions|mixed
     */
    public function create()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('CreateSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function createCombined()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('CreateCombinedSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function update()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('UpdateSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function updateCombined()
    {
        $subscription = new Subscription($this->payload);

        $payPayload = new CombinedPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('UpdateCombinedSubscription', $subscription);

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function stop()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('StopSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function info()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('SubscriptionInfo', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function deletePaymentConfig()
    {
        $subscription = new Subscription($this->payload);

        $this->setServiceList('DeletePaymentConfiguration', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function pause()
    {
        $subscription = new ResumeSubscription($this->payload);

        $this->setServiceList('PauseSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Subscriptions|mixed
     */
    public function resume()
    {
        $subscription = new ResumeSubscription($this->payload);

        $this->setServiceList('ResumeSubscription', $subscription);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }
}
