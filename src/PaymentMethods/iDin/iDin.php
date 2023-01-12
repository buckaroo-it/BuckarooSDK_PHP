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

namespace Buckaroo\PaymentMethods\iDin;

use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\iDin\Models\Issuer;
use Buckaroo\PaymentMethods\iDin\Service\ParameterKeys\IssuerAdapter;
use Buckaroo\PaymentMethods\PaymentMethod;

class IDin extends PaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'Idin';
    /**
     * @var array|string[]
     */
    protected array $requiredConfigFields = ['returnURL', 'returnURLCancel', 'returnURLError', 'returnURLReject'];

    /**
     * @return iDin|mixed
     */
    public function identify()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('identify', $issuer);

        return $this->dataRequest();
    }

    /**
     * @return iDin|mixed
     */
    public function verify()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('verify', $issuer);

        return $this->dataRequest();
    }

    /**
     * @return iDin|mixed
     */
    public function login()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('login', $issuer);

        return $this->dataRequest();
    }
}
