<?php

namespace Jaifire\Brisk\File;


class Stream
{
    /**
     * 在浏览器中下载文件
     * @param $file_path
     * @param $name
     */
    public static function download($file_path, $name = null)
    {
        if (is_null($name)) {
            $name = mb_convert_encoding(urldecode(basename($file_path)), "gb2312", "utf-8");
        }
        $file_path = mb_convert_encoding(urldecode($file_path), "gb2312", "utf-8");
        $content = file_get_contents($file_path);
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . strlen($content));
        header("Content-Disposition: attachment; filename=$name");
        echo $content;
        exit();
    }
}