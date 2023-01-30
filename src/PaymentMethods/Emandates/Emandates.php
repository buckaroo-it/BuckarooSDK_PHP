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

namespace Buckaroo\PaymentMethods\Emandates;

use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\Emandates\Models\Mandate;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PaymentMethod;

class Emandates extends PaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'emandate';
    /**
     * @var array|string[]
     */
    protected array $requiredConfigFields = ['currency'];

    /**
     * @return Emandates|mixed
     */
    public function issuerList()
    {
        $this->setServiceList('GetIssuerList');

        return $this->dataRequest();
    }

    /**
     * @return Emandates|mixed
     */
    public function createMandate()
    {
        $mandate = new Mandate($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CreateMandate', $mandate);

        return $this->dataRequest();
    }

    /**
     * @return Emandates|mixed
     */
    public function status()
    {
        $mandate = new Mandate($this->payload);

        $this->setServiceList('GetStatus', $mandate);

        return $this->dataRequest();
    }

    /**
     * @return Emandates|mixed
     */
    public function modifyMandate()
    {
        $mandate = new Mandate($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('ModifyMandate', $mandate);

        return $this->dataRequest();
    }

    /**
     * @return Emandates|mixed
     */
    public function cancelMandate()
    {
        $mandate = new Mandate($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CancelMandate', $mandate);

        return $this->dataRequest();
    }
}
