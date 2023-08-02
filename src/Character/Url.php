<?php

namespace Jaifire\Brisk\Character;

class Url
{
    /**
     * 解析url返回
     * @param string $url
     * @return array
     */
    public static function parseUrl($url)
    {
        $parse = parse_url($url);
        parse_str($parse['query'], $params);
        $parse['params'] = $params;
        return $parse;
    }
}