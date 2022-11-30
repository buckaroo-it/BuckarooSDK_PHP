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

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\iDeal;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\iDeal\Models\Pay;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class iDeal extends PayablePaymentMethod
{
    /**
     * @var string
     */
    protected string $paymentName = 'ideal';
    /**
     * @var array|string[]
     */
    protected array $requiredConfigFields = ['currency', 'returnURL', 'returnURLCancel', 'pushURL'];

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function payRemainder(?Model $model = null): TransactionResponse
    {
        return parent::payRemainder($model ?? new Pay($this->payload));
    }

    /**
     * @return \string[][]
     * @throws \Buckaroo\Exceptions\BuckarooException
     */
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
                'id' => 'FVLBNL22',
                'name' => 'Van Lanschot'
            ]
        ];

        return $issuers;
    }
}
