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

namespace Buckaroo\Services\TraitHelpers;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Transaction\Request\TransactionRequest;

trait HasIssuers
{
    /**
     * @return array
     * @throws BuckarooException
     */
    public function issuers(): array
    {
        $request = new TransactionRequest;

        try
        {
            $response = $this->client->specification($request, $this->paymentName, $this->serviceVersion());
        } catch (BuckarooException $e)
        {
            return [];
        }

        $issuerList = [];
        if (isset($response->data()['Actions']['0']['RequestParameters'][0]['ListItemDescriptions']))
        {
            $issuersData = $response->data()['Actions']['0']['RequestParameters'][0]['ListItemDescriptions'];
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
