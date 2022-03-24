<?php

/**
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

namespace Buckaroo;

use Buckaroo\Client;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Helpers\Base;

class Transaction
{
    public static function create(Client $buckarooClient, $options = array())
    {
        $request = new TransactionRequest();
        if (isset($options)) {
            foreach ($options as $optionKey => $option) {
                $optionSetMethod = 'set'.ucfirst($optionKey);                
                if (method_exists($request, $optionSetMethod) || method_exists($request, 'setServiceParameter')) {
                    if ($optionKey == 'serviceParameters') {
                        foreach ($options['serviceParameters'] as $item) {
                            $request->setServiceParameter(
                                $item['name'],
                                $item['value'],
                                $item['groupType'] ?? null,
                                $item['groupId'] ?? null
                            );
                        }
                    } elseif ($optionKey == 'issuer') {
                        $request->setServiceParameter('issuer', $option);
                    } else {
                        $request->$optionSetMethod($option);
                    }                  
                }
            }
        }

        return $buckarooClient->post(
            $request,
            'Buckaroo\Payload\TransactionResponse'
        );
    }

    public static function push($options = array())
    {
        if (isset($options['secretKey']) && isset($options['post'])) {
            if (Base::validateSignature($options['post'], $options['secretKey'])) {
                return $options['post'];
            }
        }
        return ['error' => 'Data not valid'];
    }
}
