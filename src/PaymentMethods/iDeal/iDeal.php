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
use Buckaroo\Transaction\Request\TransactionRequest;
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
     * @return \mixed
     * @throws \Buckaroo\Exceptions\BuckarooException
     */
    public function issuers() : mixed
    {
        $request = new TransactionRequest;

        try
        {
            $response = $this->client->specification($request, 'ideal', 2);
        }
        catch (BuckarooException $e)
        {
            return false;
        }

        $issuerList = [];
        if (isset($response['Actions']['0']['RequestParameters'][0]['ListItemDescriptions']))
        {
            $issuersData = $response['Actions']['0']['RequestParameters'][0]['ListItemDescriptions'];
            if (count($issuersData) > 0)
            {
                foreach ($issuersData as $issuer)
                {
                    $issuerList[] = ['id' => $issuer['Value'], 'name' => $issuer['Description']];
                }
            }
        }

        return $issuerList;
    }
}
