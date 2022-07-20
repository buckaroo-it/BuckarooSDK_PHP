<?php

namespace Buckaroo\Handlers\HMAC;

abstract class Hmac
{
    public function uri($uri = null)
    {
        if($uri)
        {
            $uri = preg_replace( "#^[^:/.]*[:/]+#i", "", $uri);

            $this->uri = strtolower(urlencode($uri));
        }

        return $this->uri;
    }

    public function base64Data($data = null)
    {
        if($data)
        {
            if(is_array($data))
            {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }

            $md5  = md5($data, true);

            $this->base64Data = base64_encode($md5);
        }

        return $this->base64Data;
    }

    public function nonce($nonce = null)
    {
        if($nonce)
        {
            $this->nonce = $nonce;
        }

        return $this->nonce;
    }

    public function time($time = null)
    {
        if($time)
        {
            $this->time = $time;
        }

        return $this->time;
    }
}