<?php

if(! function_exists('str_random')){
    function str_random(int $length = 16): string {
        $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
        $str   = "";

        for ($i = 0; $i < $length; $i++) {
            $key = array_rand($chars);
            $str .= $chars[$key];
        }

        return $str;
    }
}