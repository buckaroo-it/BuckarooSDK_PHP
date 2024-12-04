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

namespace Buckaroo\Handlers\HMAC;

abstract class Hmac
{
    /**
     * @param $uri
     * @return string
     */
    public function uri($uri = null)
    {
        if ($uri)
        {
            $uri = preg_replace("#^[^:/.]*[:/]+#i", "", $uri);

            $this->uri = strtolower(urlencode($uri));
        }

        return $this->uri;
    }

    /**
     * @param $data
     * @return string
     */
    public function base64Data($data = null)
    {
        $this->base64Data = '';

        if ($data)
        {
            if (is_array($data))
            {
                $data = mb_convert_encoding(
                    json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION),
                    'UTF-8',
                    'auto'
                );
            }

            $md5 = md5($data, true);

            $this->base64Data = base64_encode($md5);
        }

        return $this->base64Data;
    }

    /**
     * @param $nonce
     * @return mixed
     */
    public function nonce($nonce = null)
    {
        if ($nonce)
        {
            $this->nonce = $nonce;
        }

        return $this->nonce;
    }

    /**
     * @param $time
     * @return mixed
     */
    public function time($time = null)
    {
        if ($time)
        {
            $this->time = $time;
        }

        return $this->time;
    }
}
