<?php

if (!function_exists('randomStr')) {
    function randomStr($len = 16, $pool = null)
    {
        $str_pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        if (1 === $pool) {
            $str_pool = '0123456789';
        } else if (2 === $pool) {
            $str_pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        } else if (3 === $pool) {
            $str_pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else if (4 === $pool) {
            $str_pool = 'abcdefghijklmnopqrstuvwxyz';
        } else if (5 === $pool) {
            $str_pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else if (6 === $pool) {
            $str_pool = '0123456789abcdefghijklmnopqrstuvwxyz';
        } else if (7 === $pool) {
            return substr(str_shuffle('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890'), 0, $len);
        }

        $str = '';
        $max = strlen($str_pool) - 1;
        for ($i = 0; $i < $len; $i++) {
            $str .= $str_pool[mt_rand(0, $max)];
        }
        return $str;
    }
}

if (!function_exists('zeroFill')) {
    function zeroFill($str, $len)
    {
        return str_pad($str, $len, '0', STR_PAD_LEFT);
    }
}