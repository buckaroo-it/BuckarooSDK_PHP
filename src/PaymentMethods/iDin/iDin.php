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

use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\iDin\Models\Issuer;
use Buckaroo\PaymentMethods\iDin\Service\ParameterKeys\IssuerAdapter;
use Buckaroo\PaymentMethods\PaymentMethod;

class iDin extends PaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'idin';
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

        $this->setServiceList('identify', $issuer);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return iDin|mixed
     */
    public function verify()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $this->setServiceList('verify', $issuer);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return iDin|mixed
     */
    public function login()
    {
        $issuer = new IssuerAdapter(new Issuer($this->payload));

        $this->setServiceList('login', $issuer);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }
}
