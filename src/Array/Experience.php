<?php

namespace Exp\Brisk\Array;


class Experience
{
    /**
     * 二维数组排序
     */
    function array_sort($arr, $field)
    {
        $score_column = array_column($arr, $field);
        array_multisort($score_column, SORT_DESC, $arr);
        return $arr;
    }

    function arrayToTree($data, &$tree_data, $pid = 0, $main_field = 'id', $pid_field = 'pid', $child_field = 'children')
    {
        foreach ($data as $val) {
            if ($val[$pid_field] == $pid) {
                $child = [];
                self::ArrayToTree($data, $child, $val[$main_field], $main_field, $pid_field, $child_field);
                if (count($child) > 0) {
                    $val[$child_field] = $child;
                }
                $tree_data[] = $val;
            }
        }
    }
}
