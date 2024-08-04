<?php

namespace Jaifire\Brisk\File;

class Reader
{
    // 读文件
    public static function readFileToArr(string $filepath, $need_cnt = false): array
    {
        $fp = fopen($filepath, 'r');
        $cnt = 0;
        $data = [];
        while ($row = fgets($fp)) {
            $cnt += 1;
            $row = trim($row);
            if ($row) {
                $data[] = $row;
            }
        }
        if ($need_cnt) {
            return [$data, $cnt];
        }
        return $data;
    }
}