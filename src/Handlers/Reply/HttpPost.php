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

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;

class HttpPost implements ReplyStrategy
{
    /**
     * @var Config
     */
    private Config $config;
    /**
     * @var array
     */
    private array $data;

    /**
     * @param Config $config
     * @param array $data
     */
    public function __construct(Config $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        //Remove brq_signature from the equation
        $data = array_filter($this->data, function($key){
            return $key != 'brq_signature';
        }, ARRAY_FILTER_USE_KEY);

        //Combine the array keys with value
        $data = array_map(function($value, $key){
            return $key . '=' . $value;
        }, $data, array_keys($data));

        $dataString = implode('',  $data) . trim($this->config->secretKey());

        return hash_equals(sha1($dataString), trim($this->data['brq_signature'] ?? null));
    }
}