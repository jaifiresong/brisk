<?php

class Experience
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

    /**
     * 二维数组排序
     */
    function array_sort($arr, $field)
    {
        $score_column = array_column($arr, $field);
        array_multisort($score_column, SORT_DESC, $arr);
        return $arr;
    }




    /**
     * @desc 根据两点间的经纬度计算距离
     * @param array $A [104.145759, 30.634445]
     * @param array $B [104.153808, 30.681665]
     * @return float 单位米
     */
    function distance2l(array $A, array $B): float
    {
        list($longitude1, $latitude1) = $A;
        list($longitude2, $latitude2) = $B;
        $longitude1 = ($longitude1 * pi()) / 180;
        $latitude1 = ($latitude1 * pi()) / 180;
        $longitude2 = ($longitude2 * pi()) / 180;
        $latitude2 = ($latitude2 * pi()) / 180;
        $calcLongitude = $longitude2 - $longitude1;
        $calcLatitude = $latitude2 - $latitude1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($latitude1) * cos($latitude2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = 6367000 * $stepTwo;//approximate radius of earth in meters：6367000
        return round($calculatedDistance);
    }




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


    /**
     * 13位的时间戳
     */
    public static function millisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

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
     * @return string
     */
    public static function b64ToImage($code)
    {
        $str = str_replace('data:image/png;base64,', '', $code);  //去掉指定字符串
        $str = str_replace('data:image/jpeg;base64,', '', $str);  //去掉指定字符串
        return base64_decode($str);
    }
}
