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
use Buckaroo\Handlers\HMAC\Validator;

class Json implements ReplyStrategy
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
     * @var string
     */
    private string $auth_header;
    /**
     * @var string
     */
    private string $uri;
    /**
     * @var string|mixed
     */
    private string $method;

    /**
     * @param Config $config
     * @param array $data
     * @param string $auth_header
     * @param string $uri
     * @param $method
     */
    public function __construct(Config $config, array $data, string $auth_header, string $uri, $method = 'POST')
    {
        $this->config = $config;
        $this->data = $data;
        $this->auth_header = $auth_header;
        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validator = new Validator($this->config);

        return $validator->validate($this->auth_header, $this->uri, $this->method, $this->data);
    }
}
