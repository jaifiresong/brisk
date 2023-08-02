<?php

namespace Jaifire\Brisk\Character;
class PCRE
{
    public static function is_url($url)
    {
        return preg_match('/(http|https):\/\/(.*)[\.]/', $url);
    }

    function replace($content, $baseUrl = 'http:127.0.0.1/')
    {
        return preg_replace_callback(
            '/src="(.*?)"/',
            function ($matched) use ($baseUrl) {
                if (str_starts_with($matched[1], 'http')) {
                    $img = $matched[1];
                } else {
                    $img = $baseUrl . $matched[1];
                }
                return "src=\"$img\"";
            },
            $content
        );
    }
}