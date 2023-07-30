<?php

namespace Exp\Brisk\File;


class Stream
{
    /**
     * 在浏览器中下载文件
     * @param string $file_path 原文件
     * @param string $name 下载名称
     */
    public static function download($file_path, $name = null)
    {
        if (empty($name)) {
            $name = mb_convert_encoding(urldecode(basename($file_path)), "gb2312", "utf-8");
        }
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . strlen($file_path));
        header("Content-Disposition: attachment; filename=$name");
        $file_path = mb_convert_encoding(urldecode($file_path), "gb2312", "utf-8");
        echo file_get_contents($file_path);
        exit();
    }
}