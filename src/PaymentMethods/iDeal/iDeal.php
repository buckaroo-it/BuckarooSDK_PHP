<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\iDeal;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\iDeal\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class iDeal extends PayablePaymentMethod
{
    protected string $paymentName = 'ideal';
    protected array $requiredConfigFields = ['currency', 'returnURL', 'returnURLCancel', 'pushURL'];

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    public function payRemainder(?Model $model = null): TransactionResponse
    {
        return parent::payRemainder($model ?? new Pay($this->payload));
    }

    public function issuers(): array
    {
        $issuers = [
            [
                'id' => 'ABNANL2A',
                'name' => 'ABN AMRO'
            ],
            [
                'id' => 'ASNBNL21',
                'name' => 'ASN Bank'
            ],
            [
                'id' => 'BUNQNL2A',
                'name' => 'bunq'
            ],
            [
                'id' => 'INGBNL2A',
                'name' => 'ING'
            ],
            [
                'id'    => 'KNABNL2H',
                'name'  => 'Knab'
            ],
            [
                'id' => 'RABONL2U',
                'name' => 'Rabobank'
            ],
            [
                'id' => 'RBRBNL21',
                'name' => 'RegioBank'
            ],
            [
                'id' => 'REVOLT21',
                'name' => 'Revolut'
            ],
            [
                'id' => 'SNSBNL2A',
                'name' => 'SNS Bank'
            ],
            [
                'id' => 'TRIONL2U',
                'name' => 'Triodos Bank'
            ],
            [
                'id' => 'HANDNL2A',
                'name' => 'Svenska Handelsbanken'
            ],
            [
                'id' => 'FVLBNL22',
                'name' => 'Van Lanschot'
            ]
        ];

        if(!$this->client->config()->isLiveMode())
        {
            $issuers[] = [
                'id'    => 'BANKNL2Y',
                'name' => 'TEST BANK'
            ];
        }

        return $issuers;
    }
}
