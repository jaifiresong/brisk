<?php

namespace Exp\Brisk\Crypto;

class Scale
{
    /**
     * 字符转成16制
     * @param string $str
     * @return string
     */
    public static function to_scale16($str)
    {
        $r = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $ascii = ord(substr($str, $i, 1));
            $r .= base_convert($ascii, 10, 16);
        }
        return $r;
    }

    /**
     * to_scale16的反向函数
     * @param string $str
     * @return string
     */
    public static function decode_scale16($str)
    {
        $r = '';
        for ($i = 0; $i < strlen($str); $i += 2) {
            $ori = base_convert(substr($str, $i, 2), 16, 10);
            $r .= chr($ori);
        }
        return $r;
    }
}