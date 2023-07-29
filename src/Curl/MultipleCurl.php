<?php


namespace Exp\Brisk\Curl;


class MultipleCurl
{
    public $chs;
    public $map;
    public $limit;
    public $urls_cfg;

    public function __construct(array $urls_cfg, $limit = 10)
    {
        $this->map = [];
        $this->chs = curl_multi_init();  //允许并行地处理批处理cURL的句柄
        $this->limit = $limit; //并发限制
        $this->urls_cfg = $urls_cfg;
    }

    private function request($callback = null)
    {
        while (true) {
            $status = curl_multi_exec($this->chs, $active);
            if ($status != CURLM_OK) {//如果没有准备就绪，就再次调用 curl_multi_exec
                continue;
            }
            //获取结果
            while ($done = curl_multi_info_read($this->chs)) {
                $result = curl_multi_getcontent($done["handle"]);
                $info = curl_getinfo($done["handle"]);
                $error = curl_error($done["handle"]);
                $params = $this->map[strval($done["handle"])];
                $data = compact('info', 'error', 'result', 'params');
                if (is_callable($callback)) {
                    $callback($data);//回调函数
                }
                curl_multi_remove_handle($this->chs, $done['handle']);
                curl_close($done['handle']);
            }
            if ($active <= 0) break;
            usleep(1000);
        }
    }

    private function add_ch(array $arr, $method)
    {
        foreach ($arr as $cfg) {
            $ch = curl_init();  //初始化 cURL 会话(返回 cURL 句柄，供curl_setopt()、 curl_exec() 和 curl_close() 函数使用。),
            curl_setopt($ch, CURLOPT_URL, $cfg['url']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
            if ('post' === $method) {
                curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
                curl_setopt($ch, CURLOPT_POSTFIELDS, $cfg['data']); // Post提交的数据包
            }
            curl_multi_add_handle($this->chs, $ch);  //将$ch放到$chs
            $this->map[strval($ch)] = $cfg;
        }
    }

    private function exec($callback, $method)
    {
        $arr = [];
        foreach ($this->urls_cfg as $idx => $cfg) {
            if ($idx && 0 == $idx % $this->limit) {
                $this->add_ch($arr, $method);
                $this->request($callback);
                $arr = [];
            }
            $arr[] = $cfg;
        }
        if (count($arr) > 0) {
            $this->add_ch($arr, $method);
            $this->request($callback);
        }
    }

    public function get($callback = null)
    {
        $this->exec($callback, 'get');
    }

    public function post($callback = null)
    {
        $this->exec($callback, 'post');
    }
}
