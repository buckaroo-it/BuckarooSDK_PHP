<?php

declare(strict_types=1);

namespace Buckaroo\Payload;

use Buckaroo\Exceptions\SdkException;
use Buckaroo\Helpers\Validate;
use Buckaroo\Helpers\Constants\IPProtocolVersion;
use Buckaroo\Helpers\Base;
use Psr\Log\LoggerInterface;

class TransactionRequest extends Request
{
    public function __construct(
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($logger);
        $this->setClientIP();
        $this->setClientUserAgent();
        $this->createDefaultService();
    }

    protected function createDefaultService(): void
    {
        if (!isset($this->data['Services'])) {
            $this->data['Services'] = [];
        }

        if (!isset($this->data['Services']['ServiceList'])) {
            $this->data['Services']['ServiceList'] = [];
        }

        if (!isset($this->data['Services']['ServiceList'][0]['Parameters'])) {
            $this->data['Services']['ServiceList'][0]['Parameters'] = [];
        }

        if (!isset($this->data['Currency'])) {
            $this->data['Currency'] = 'EUR';
        }
    }

    public function setCurrency(string $currency): void
    {
        if (!Validate::isCurrency($currency)) {
            $this->throwError(__METHOD__, "Invalid currency", $currency);
        }
        $this->data['Currency'] = $currency;
    }

    public function setClientIP(?string $ip = null): void
    {
        if (!$ip) {
            $ip = Base::getRemoteIp();
        }

        if (!Validate::isIp($ip)) {
            $this->throwError(__METHOD__, "Invalid IP", $ip);
        }

        $this->data['ClientIP'] = [
            'Type'    => IPProtocolVersion::getVersion($ip),
            'Address' => $ip,
        ];
    }

    public function setClientUserAgent(?string $userAgent = null): void
    {
        if (!$userAgent) {
            $userAgent = Base::getRemoteUserAgent();
        }

        $this->data['ClientUserAgent'] = $userAgent;
    }

    public function setMethod(string $method): void
    {
        if (!Validate::isMethod($method)) {
            $this->throwError(__METHOD__, "Invalid method name", $method);
        }

        $this->data['Services']['ServiceList'][0]['Name'] = $method;
    }

    public function getMethod(): ?string
    {
        return $this->data['Services']['ServiceList'][0]['Name'];
    }

    public function setServiceAction(string $action): void
    {
        if (!Validate::isServiceAction($action)) {
            $this->throwError(__METHOD__, "Invalid service action", $action);
        }

        $this->data['Services']['ServiceList'][0]['Action'] = $action;
    }

    public function getServiceAction(): ?string
    {
        return $this->data['Services']['ServiceList'][0]['Action'];
    }

    public function setServiceVersion(int $version)
    {
        if (!Validate::isServiceVersion($version)) {
            $this->throwError(__METHOD__, "Invalid service version", $version);
        }

        $this->data['Services']['ServiceList'][0]['Version'] = $version;
    }

    public function getServiceVersion(): ?int
    {
        return $this->data['Services']['ServiceList'][0]['Version'];
    }

    private function throwError(string $method, $message, $value): void
    {
        throw new SdkException($this->logger, $method, "$message: '{$value}'");
    }

    public function setServiceParameter(
        string $name,
        string $value,
        ?string $groupType = null,
        ?string $groupId = null
    ): array {
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

    public function getServiceParameter(
        string $name,
        ?string $groupType = null,
        ?string $groupId = null
    ) {
        foreach ($this->data['Services']['ServiceList'][0]['Parameters'] as $i => $param) {
            if (
                $param['Name'] === $name &&
                (is_null($groupType) || (isset($param['GroupType']) && $param['GroupType'] === $groupType)) &&
                (is_null($groupId) || (isset($param['GroupID']) && $param['GroupID'] === $groupId))
            ) {
                return $param['Value'];
            }
        }

        return '';
    }


    public function setCustomParameter(string $key, string $value): string
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

    public function setAdditionalParameter(string $key, string $value): string
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

    public function setServicesSelectableByClient(string $services): void
    {
        $this->data['ServicesSelectableByClient'] = $services;
    }

    public function setContinueOnIncomplete(string $value): void
    {
        $this->data['ContinueOnIncomplete'] = $value;
    }

    public function removeServices(): void
    {
        unset($this->data['Services']);
        unset($this->data['Services']['ServiceList']);
        unset($this->data['Services']['ServiceList'][0]);
        unset($this->data['Services']['ServiceList'][0]['Parameters']);
    }

    public function setReturnURL(string $url): void
    {
        $this->data['ReturnURL'] = $url;
    }

    public function setReturnURLCancel(string $url): void
    {
        $this->data['ReturnURLCancel'] = $url;
    }

    public function setReturnURLError(string $url): void
    {
        $this->data['ReturnURLError'] = $url;
    }

    public function setReturnURLReject(string $url): void
    {
        $this->data['ReturnURLReject'] = $url;
    }

    public function setPushURL(string $url): void
    {
        $this->data['PushURL'] = $url;
    }

    public function setPushURLFailure(string $url): void
    {
        $this->data['PushURLFailure'] = $url;
    }

    public function setAmountCredit(float $amount): void
    {
        $this->data['AmountCredit'] = $amount;
    }

    public function getAmountCredit(): ?float
    {
        return $this->data['AmountCredit'];
    }

    public function setAmountDebit(float $amount): void
    {
        $this->data['AmountDebit'] = $amount;
    }

    public function getAmountDebit(): ?float
    {
        return $this->data['AmountDebit'];
    }

    public function setOrder(string $order): void
    {
        if (!Validate::isOrder($order)) {
            $this->throwError(__METHOD__, "Invalid order number", $order);
        }

        $this->data['Order'] = $order;
    }

    public function getOrder(): string
    {
        return $this->data['Order'];
    }

    public function setInvoice(string $invoice): void
    {
        if (!Validate::isInvoice($invoice)) {
            $this->throwError(__METHOD__, "Invalid invoice number", $invoice);
        }

        $this->data['Invoice'] = $invoice;
    }

    public function getInvoice(): ?string
    {
        return $this->data['Invoice'];
    }

    public function setOriginalTransactionKey(string $transactionKey): void
    {
        if (!Validate::isOriginalTransactionKey($transactionKey)) {
            $this->throwError(__METHOD__, "Invalid original transaction key", $transactionKey);
        }

        $this->data['OriginalTransactionKey'] = $transactionKey;
    }

    public function getOriginalTransactionKey(): ?string
    {
        return $this->data['OriginalTransactionKey'];
    }

    public function setChannelHeader(string $channel): void
    {
        $channel = ucfirst(strtolower($channel));

        if (!Validate::isChannelHeader($channel)) {
            $this->throwError(__METHOD__, "Invalid channel (should be set to 'Web' or 'Backoffice')", $channel);
        }

        $this->setHeader('Channel', $channel);
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
}
