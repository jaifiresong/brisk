<?php


namespace Exp\Brisk;

/*
 *  应纳税收入 = 总收入 - 总免税扣除
 *  纳税额 = 应纳税收入 * 税率 - 速算
 *
 * 2019年开始新的税法：按年度综合所得实行扣税
 * 一年有6万的免征
 * 专项扣除：三险一金
 * 附加扣除：子女教育（1.2W），大病医疗（最多8W），房贷（1.2W），房租（1.8W），赡养老人（1.2W）
 */


class Tax
{
    private function grade($taxable)
    {
        if ($taxable <= 36000) return [0, 0.03];
        if ($taxable <= 144000) return [2520, 0.1];
        if ($taxable <= 300000) return [16920, 0.2];
        if ($taxable <= 420000) return [31920, 0.25];
        if ($taxable <= 660000) return [52920, 0.3];
        if ($taxable <= 960000) return [85920, 0.35];
        //$taxable > 960000
        return [181920, 0.45];
    }

    /**
     * @param float $amount 总收入
     * @param float $exempt 总免税扣除
     */
    public function calc($amount, $exempt = 0)
    {
        $taxable = $amount - $exempt;
        list($rapid, $rate) = $this->grade($taxable);
        $tax = $taxable * $rate - $rapid;
        echo json_encode(['应纳税收入' => $taxable, '应纳税额' => $tax, '税率' => $rate], JSON_UNESCAPED_UNICODE), PHP_EOL;
        return $tax;
    }
}




