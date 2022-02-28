<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;

class Ideal extends PaymentMethod
{
    public const BANK_CODE_ABN = 'ABNANL2A';
    public const BANK_CODE_ASN = 'ASNBNL21';
    public const BANK_CODE_BUNQ = 'BUNQNL2A';
    public const BANK_CODE_ING = 'INGBNL2A';
    public const BANK_CODE_RABO = 'RABONL2U';
    public const BANK_CODE_REGIO = 'RBRBNL21';
    public const BANK_CODE_SNS = 'SNSBNL2A';
    public const BANK_CODE_TRIODOS = 'TRIONL2U';
    public const BANK_CODE_TEST = 'BANKNL2Y';

    public function getCode(): string
    {
        return PaymentMethod::IDEAL;
    }

    public function pay(TransactionRequest $request): TransactionResponse
    {
        $request->setServiceVersion(2);
        return parent::pay($request);
    }

    protected function validatePayRequest(TransactionRequest $request): void
    {
        if (!$request->getServiceParameter('issuer')) {
            $this->throwError(__METHOD__, "Empty bank code");
        }
        parent::validatePayRequest($request);
    }

    public function setBankCode(TransactionRequest $request, string $bankCode): void
    {
        if (!in_array($bankCode, $this->getBanks())) {
            $this->throwError(__METHOD__, "Invalid bank code", $bankCode);
        }

        $request->setServiceParameter('issuer', $bankCode);
    }

    public function getBanks(): array
    {
        return [
            self::BANK_CODE_ABN,
            self::BANK_CODE_ASN,
            self::BANK_CODE_BUNQ,
            self::BANK_CODE_ING,
            self::BANK_CODE_RABO,
            self::BANK_CODE_REGIO,
            self::BANK_CODE_SNS,
            self::BANK_CODE_TRIODOS,
        ];
    }

    public function getBanksNames()
    {
        $issuers = [
            [
                'servicename' => self::BANK_CODE_ABN,
                'displayname' => 'ABN AMRO',
            ],
            [
                'servicename' => self::BANK_CODE_ASN,
                'displayname' => 'ASN Bank',
            ],
            [
                'servicename' => self::BANK_CODE_BUNQ,
                'displayname' => 'bunq',
            ],
            [
                'servicename' => self::BANK_CODE_ING,
                'displayname' => 'ING',
            ],
            [
                'servicename' => self::BANK_CODE_RABO,
                'displayname' => 'Rabobank',
            ],
            [
                'servicename' => self::BANK_CODE_REGIO,
                'displayname' => 'RegioBank',
            ],
            [
                'servicename' => self::BANK_CODE_SNS,
                'displayname' => 'SNS Bank',
            ],
            [
                'servicename' => self::BANK_CODE_TRIODOS,
                'displayname' => 'Triodos Bank',
            ],
        ];

        return $issuers;
    }
}
