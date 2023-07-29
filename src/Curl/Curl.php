<?php

namespace Exp\Brisk\Curl;


class Curl
{
    /**
     * post请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @return string 返回页面结果
     */
    public static function post($url, $data, $timeout = 5)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //如果没有这个curl_exec会执行显示操作
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包,是一个数组
        //curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作(里面有了输出显示的操作了，可以使用CURLOPT_RETURNTRANSFER让其不显示)
        if (curl_errno($curl)) {
            echo 'Errno：' . curl_error($curl); //捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $res;
    }

    /**
     * get请求
     * @param string $url
     * @param int $timeout
     * @return string 返回页面结果
     */
    public static function get($url, $timeout = 15)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $contents = curl_exec($ch);
        curl_close($ch);
        return $contents;
    }

    /**
     * 并发请求
     * @param array $urls
     * @param callable $callback
     */
    public static function multiple_request($urls = array(), $callback = null)
    {
        $chs = curl_multi_init();  //初始化（允许并行地处理批处理cURL的句柄。）
        $map = array();
        foreach ($urls as $url) {
            $ch = curl_init();  //初始化 cURL 会话(返回 cURL 句柄，供curl_setopt()、 curl_exec() 和 curl_close() 函数使用。),
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_multi_add_handle($chs, $ch);  //将$ch放到$chs
            $map[strval($ch)] = $url;
        }
        while (true) {
            $status = curl_multi_exec($chs, $active);
            usleep(rand(10 * 1000, 100 * 1000));  //一定要加这个，不然服务器CPU直飙到100%
            if ($status != CURLM_CALL_MULTI_PERFORM) {  //如过并发没有执行完
                if ($status != CURLM_OK) {
                    continue;  //如果没有准备就绪，就再次调用curl_multi_exec
                }
                while ($done = curl_multi_info_read($chs)) {
                    $info = curl_getinfo($done["handle"]);
                    $error = curl_error($done["handle"]);
                    $result = curl_multi_getcontent($done["handle"]);
                    $url = $map[strval($done["handle"])];
                    $rtn = compact('info', 'error', 'result', 'url');
                    if (trim($callback)) {
                        $callback($rtn);  //用回调函数来处理反回的数据，当然也可以不传回调函数，而单独写一个函数来调用
                    }
                    curl_multi_remove_handle($chs, $done['handle']);
                    curl_close($done['handle']);
                    //如果仍然有未处理完毕的句柄，那么就select
                    if ($active > 0) {
                        curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                    }
                }
            }
            if ($active <= 0) {
                break;
            }
        }
        curl_multi_close($chs);
    }

}