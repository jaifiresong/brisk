<?php

namespace Jaifire\Brisk\Character;


class Formatter
{
    public static function time2pretty($remain): string
    {
        $day = floor($remain / 86400);  //计算天
        $remain = $remain % 86400;
        $hour = floor($remain / 3600);  //计算小时
        $remain = $remain % 3600;
        $minute = floor($remain / 60);  //计算分
        $second = $remain % 60;  //剩余的秒数

        $pretty = '';
        if ($day) {
            $pretty .= "$day 天";
        }
        if ($hour) {
            $pretty .= " $hour 小时";
        }
        if ($minute) {
            $pretty .= " $minute 分";
        }
        if ($second) {
            $pretty .= " $second 秒";
        }
        return $pretty;
    }

    public static function amountInWords($number)
    {
        $char = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
        $map = ['', '拾', '佰', '仟', '万', '拾', '佰', '仟', '亿', '拾', '佰', '仟', '万亿'];
        $arr = str_split($number);
        if (count($arr) > count($map)) {
            return false;
        }
        $map = array_reverse(array_slice($map, 0, count($arr)));
        $format = '';
        foreach ($arr as $i => $n) {
            if (0 == $n) {
                $format .= in_array($map[$i], ['万', '亿']) ? $map[$i] : '零';
            } else {
                $format .= $char[$n] . $map[$i];
            }
            if (mb_substr($format, -2) == '零零') {
                $format = mb_substr($format, 0, -2) . '零';
            }
            foreach (['拾', '佰', '仟', '万', '亿'] as $t) {
                if (mb_substr($format, -2) == '零' . $t) {
                    $format = mb_substr($format, 0, -2) . $t;
                }
            }
        }
        if (mb_substr($format, -1) == '零') {
            $format = mb_substr($format, 0, -1);
        }
        return $format;
    }

    /**
     * 数字转人民币汉字大写，最高到亿
     * @param string $number
     * @return string
     */
    public static function numberToMoney($number)
    {
        if (!is_numeric($number)) {
            return false;
        }
        $char = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        $unit = array('', '拾', '佰', '仟', '', '拾', '佰', '仟', '', '拾');
        $unit4 = array('万', '亿');
        $numberArr = explode('.', $number);
        $inte = $numberArr[0];
        $num_len = strlen($inte);
        $inteArr = array();
        for ($i = 0; $i < $num_len; $i++) {
            $inteArr[] = substr($inte, -($i + 1), 1);
        }
        foreach ($inteArr as $k => $v) {
            if ($v) {
                $inteArr[$k] = $char[$v] . $unit[$k];
            } else {
                $inteArr[$k] = $char[$v];
            }
            if ($k > 0 && 0 == $k % 4) {
                $inteArr[$k] .= $unit4[($k / 4 - 1)];
            }
            if ($k > 3) {
                $inteArr[$k] = str_replace('零', '', $inteArr[$k]);
            }
        }
        $yuan = join('', array_reverse($inteArr));
        $yuan = preg_replace('/[零]+$/', '', $yuan);
        $yuan = preg_replace('/[零]{2,}/', '零', $yuan) . '元';
        $yuan = '元' === $yuan ? '' : $yuan;
        //小数部份
        if (!empty($numberArr[1])) {
            $dec = $numberArr[1];
            if (empty($dec[0]) && empty($dec[1])) {
                $yuan .= '整';
            } else {
                $dec[1] = empty($dec[1]) ? 0 : $dec[1];
                $jiao_fen = "{$char[$dec[0]]}角{$char[$dec[1]]}分";
                $yuan .= $jiao_fen;
            }
        } else {
            $yuan .= '整';
        }
        return $yuan;
    }
}

var_dump(Formatter::time2pretty(0));
var_dump(Formatter::time2pretty(1));
var_dump(Formatter::time2pretty(65));
var_dump(Formatter::time2pretty(3650));
var_dump(Formatter::time2pretty(7200));
var_dump(Formatter::time2pretty(864061));
var_dump(Formatter::time2pretty(1864061));