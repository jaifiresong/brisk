<?php

namespace Jaifire\Brisk\Image;

class B64
{
    /**
     * 图片转成base64码
     * @param $file_path
     * @return string
     */
    public static function imageToB64($file_path)
    {
        $handle = fopen($file_path, 'r');
        $image_data = fread($handle, filesize($file_path));
        fclose($handle);
        return base64_encode($image_data);
    }

    /**
     * base64码转图片
     * @param string $code
     */
    public static function b64ToImage($code)
    {
        $str = str_replace('data:image/png;base64,', '', $code);  //去掉指定字符串
        $str = str_replace('data:image/jpeg;base64,', '', $str);  //去掉指定字符串
        return base64_decode($str);
    }
}