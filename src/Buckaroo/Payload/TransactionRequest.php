<?php declare (strict_types = 1);

namespace Buckaroo\SDK\Buckaroo\Payload;

use Exception;
use Buckaroo\SDK\Buckaroo\Payload\Request;
use Buckaroo\SDK\Helpers\Constants\IPProtocolVersion;
use Buckaroo\SDK\Helpers\Helpers;

class TransactionRequest extends Request
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setClientIP();
        $this->setClientUserAgent();
        $this->createDefaultService();

    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->data['Currency'] = $currency;
    }

    /**
     * Set the remote IP of the customer
     */
    public function setClientIP()
    {
        $remoteIp = Helpers::getRemoteIp();

        $this->data['ClientIP'] = [
            'Type'    => IPProtocolVersion::getVersion($remoteIp),
            'Address' => $remoteIp,
        ];
    }

    /**
     * Set the user agent of the request
     */
    public function setClientUserAgent()
    {
        $this->data['ClientUserAgent'] = Helpers::getRemoteUserAgent();
    }

    /**
     * Create a service skeleton in the data
     */
    protected function createDefaultService()
    {
        if (!isset($this->data['Services'])) {
            $this->data['Services'] = [];
        }

        if (!isset($this->data['Services']['ServiceList'])) {
            $this->data['Services']['ServiceList'] = [];
        }

        if (!isset($this->data['Services']['ServiceList'][0])) {
            $this->data['Services']['ServiceList'][0] = ['Action' => 'Pay', 'Version' => 1, 'Name' => '', 'Parameters' => []];
        }

        if (!isset($this->data['Services']['ServiceList'][0]['Parameters'])) {
            $this->data['Services']['ServiceList'][0]['Parameters'] = [];
        }

    }

    /**
     * @param string $name
     */
    public function setServiceName($name)
    {
        $this->data['Services']['ServiceList'][0]['Name'] = $name;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->data['Services']['ServiceList'][0]['Name'];
    }

    /**
     * @param string $action
     */
    public function setServiceAction($action)
    {
        $this->data['Services']['ServiceList'][0]['Action'] = $action;
    }

    /**
     * @return string
     */
    public function getServiceAction()
    {
        return $this->data['Services']['ServiceList'][0]['Action'];
    }

    /**
     * @param string $version
     */
    public function setServiceVersion($version)
    {
        $this->data['Services']['ServiceList'][0]['Version'] = $version;
    }

    /**
     * @return string
     */
    public function getServiceVersion()
    {
        return $this->data['Services']['ServiceList'][0]['Version'];
    }

    /**
     * @param string $key
     * @param string $value
     * @param string|null $groupType
     * @param string|null $groupId
     * @return string $value
     */
    public function setServiceParameter($name, $value, $groupType = null, $groupId = null)
    {
        $newParam = [
            'Name'  => $name,
            'Value' => $value,
        ];

        if ($groupType) {
            $newParam['GroupType'] = $groupType;
        }

        if ($groupId) {
            $newParam['GroupID'] = $groupId;
        }

        foreach ($this->data['Services']['ServiceList'][0]['Parameters'] as $i => $param) {
            if (
                $param['Name'] === $name &&
                (is_null($groupType) || (isset($param['GroupType']) && $param['GroupType'] === $groupType)) &&
                (is_null($groupId) || (isset($param['GroupID']) && $param['GroupID'] === $groupId))
            ) {
                $this->data['Services']['ServiceList'][0]['Parameters'][$i] = $newParam;
                return $newParam;
            }
        }

        $this->data['Services']['ServiceList'][0]['Parameters'][] = $newParam;

        return $newParam;
    }

    /**
     * @param string $key
     * @param string $value
     * @return string $value
     */
    public function setCustomParameter($key, $value)
    {
        if (!isset($this->data['CustomParameters'])) {
            $this->data['CustomParameters'] = [];
        }

        if (!isset($this->data['CustomParameters']['List'])) {
            $this->data['CustomParameters']['List'] = [];
        }

        foreach ($this->data['CustomParameters']['List'] as $i => $custom) {
            $name = $custom['Name'];

            if ($name === $key) {
                $this->data['CustomParameters']['List'][$i]['Value'] = $value;
                return $value;
            }
        }

        $this->data['CustomParameters']['List'][] = [
            'Name'  => $key,
            'Value' => $value,
        ];

        return $value;
    }

    /**
     * Set an additional parameter
     * Structure is AdditionalParameters -> AdditionalParameter
     *
     * @param string $key
     * @param string $value
     * @return string $value
     */
    public function setAdditionalParameter($key, $value)
    {
        if (!isset($this->data['AdditionalParameters'])) {
            $this->data['AdditionalParameters'] = [];
        }

        if (!isset($this->data['AdditionalParameters']['AdditionalParameter'])) {
            $this->data['AdditionalParameters']['AdditionalParameter'] = [];
        }

        foreach ($this->data['AdditionalParameters']['AdditionalParameter'] as $i => $additional) {
            $name = $additional['Name'];

            if ($name === $key) {
                $this->data['AdditionalParameters']['AdditionalParameter'][$i]['Value'] = $value;
                return $value;
            }
        }

        $this->data['AdditionalParameters']['AdditionalParameter'][] = [
            'Name'  => $key,
            'Value' => $value,
        ];

        return $value;
    }

    /**
     * Pay parameters
     */

    /**
     * @param string $services
     *
     * Giftcard specific
     */
    public function setServicesSelectableByClient($services)
    {
        $this->data['ServicesSelectableByClient'] = $services;
    }

    /**
     * @param string $value
     *
     * Giftcard specific
     */
    public function setContinueOnIncomplete($value)
    {
        $this->data['ContinueOnIncomplete'] = $value;
    }

    /*
     * Remove data Services.
     *
     * Giftcard specific
     */
    public function removeServices()
    {
        unset($this->data['Services']);
        unset($this->data['Services']['ServiceList']);
        unset($this->data['Services']['ServiceList'][0]);
        unset($this->data['Services']['ServiceList'][0]['Parameters']);
    }

    /**
     * @param string $url
     */
    public function setReturnURL($url)
    {
        $this->data['ReturnURL'] = $url;
    }

    /**
     * @param string $url
     */
    public function setReturnURLCancel($url)
    {
        $this->data['ReturnURLCancel'] = $url;
    }

    /**
     * @param string $url
     */
    public function setReturnURLError($url)
    {
        $this->data['ReturnURLError'] = $url;
    }

    /**
     * @param string $url
     */
    public function setReturnURLReject($url)
    {
        $this->data['ReturnURLReject'] = $url;
    }

    /**
     * @param string $url
     */
    public function setPushURL($url)
    {
        $this->data['PushURL'] = $url;
    }

    /**
     * @param string $url
     */
    public function setPushURLFailure($url)
    {
        $this->data['PushURLFailure'] = $url;
    }

    /**
     * @param string $signature
     */
    public function setSignature($signature)
    {
        return $this->setAdditionalParameter('signature', $signature);
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        return $this->setAdditionalParameter('token', $token);
    }

    /**
     * Refund parameters
     */

    /**
     * @param float $amount
     */
    public function setAmountCredit($amount)
    {
        $this->data['AmountCredit'] = $amount;
    }

    /**
     * @return float
     */
    public function getAmountCredit()
    {
        return $this->data['AmountCredit'];
    }

    /**
     * @param string $transactionKey
     */
    public function setOriginalTransactionKey($transactionKey)
    {
        $this->data['OriginalTransactionKey'] = $transactionKey;
    }

    /**
     * @return string
     */
    public function getOriginalTransactionKey()
    {
        return $this->data['OriginalTransactionKey'];
    }

    /**
     * Header fields
     */

    /**
     * @param string $channel
     */
    public function setChannelHeader($channel)
    {
        $channel = ucfirst(strtolower($channel));

        if (!in_array($channel, ['Web', 'Backoffice'])) {
            throw new Exception('Channel should be set to "Web" or "Backoffice"');
        }

        return $this->setHeader('Channel', $channel);
    }

    /** Implement JsonSerializable */
    public function jsonSerialize()
    {
        // make sure the keys are in the correct order

        $order = array_flip([
            'Currency',
            'AmountDebit',
            'AmountCredit',
            'Invoice',
            'Order',
            'Description',
            'ClientIP',
            'ReturnURL',
            'ReturnURLCancel',
            'ReturnURLError',
            'ReturnURLReject',
            'OriginalTransactionKey',
            'StartRecurrent',
            'ContinueOnIncomplete',
            'ServicesSelectableByClient',
            'ServicesExcludedForClient',
            'PushURL',
            'PushURLFailure',
            'ClientUserAgent',
            'OriginalTransactionReference',
            'Services',
            'CustomParameters',
            'AdditionalParameters',
            'CustomerCardName',
        ]);

        // sort on key
        uksort($this->data, function ($a, $b) use ($order) {
            $aKey = isset($order[$a]) ? $order[$a] : 9999;
            $bKey = isset($order[$b]) ? $order[$b] : 9999;

            if ($aKey == $bKey) {
                return 0;
            }

            return ($aKey < $bKey) ? -1 : 1;
        });
        return $this->data;
    }

    public function setCustomerCardName($customerCardName)
    {
        $this->data['CustomerCardName'] = $customerCardName;
    }

    /**
     * @return float
     */
    public function getcustomerCardName()
    {
        return $this->data['CustomerCardName'];
    }

}
