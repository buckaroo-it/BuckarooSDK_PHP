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

namespace Buckaroo\Models;

use Buckaroo\Resources\Constants\IPProtocolVersion;

class ClientIP extends Model
{
    /**
     * @var
     */
    protected $Type;
    protected $Address;

    /**
     * @param string|null $ip
     * @param int|null $type
     */
    public function __construct(?string $ip = null, ?int $type = null)
    {
        $this->setAddress($ip);
        $this->setType($type);
    }

    /**
     * @param string|null $ip
     * @return $this
     */
    private function setAddress(?string $ip)
    {
        $this->Address = $ip ?? $this->getRemoteIp();

        return $this;
    }

    /**
     * @param int|null $type
     * @return $this
     */
    private function setType(?int $type)
    {
        $this->Type = $type ?? IPProtocolVersion::getVersion($this->Address);

        return $this;
    }

    /**
     * @return mixed|string
     */
    private function getRemoteIp()
    {
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : $_SERVER;

        /**
         * Get the forwarded IP if it exists
         */
        if (isset($headers['X-Forwarded-For']) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP))
        {
            return $headers['X-Forwarded-For'];
        }

        if (isset($headers['HTTP_X_FORWARDED_FOR']) &&
               filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)
        ) {
            return $headers['HTTP_X_FORWARDED_FOR'];
        }

        if (isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP))
        {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '127.0.0.1';
    }
}
