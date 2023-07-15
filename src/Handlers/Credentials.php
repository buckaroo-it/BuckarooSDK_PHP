<?php
/*
 *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the MIT License
 *  * It is available through the world-wide-web at this URL:
 *  * https://tldrlegal.com/license/mit-license
 *  * If you are unable to obtain it through the world-wide-web, please send an email
 *  * to support@buckaroo.nl so we can send you a copy immediately.
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this module to newer
 *  * versions in the future. If you wish to customize this module for your
 *  * needs please contact support@buckaroo.nl for more information.
 *  *
 *  * @copyright Copyright (c) Buckaroo B.V.
 *  * @license   https://tldrlegal.com/license/mit-license
 *
 */

namespace Buckaroo\Handlers;

use Buckaroo\Config\Config;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Models\ServiceList;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\TransactionRequest;

class Credentials
{
    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @param Client $client
     * @param Config $config
     */
    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function confirm(): bool
    {
        $serviceList = new ServiceList('ideal', 2, 'Specification');

        $request = new TransactionRequest;

        try
        {
            $response = $this->client->specification($request, 'ideal', 2);
        } catch (BuckarooException $e)
        {
            return false;
        }

        if ($response->getHttpResponse()->getStatusCode() == 200)
        {
            return true;
        }

        return false;
    }
}
