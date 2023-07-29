<?php

namespace Test;

use Exp\Brisk\Tax;
use PHPUnit\Framework\TestCase;


class TaxTest extends TestCase
{
    public function test()
    {
        //(new Tax())->calc(12500 * 12, (5000 + 1000 + 337 + 89) * 12);
        //(new Tax())->calc(12500 * 12, (5000 + 337 + 89) * 12);
        //(new Tax())->calc(12500 * 12, 12000+5282+60000);
        //(new Tax())->calc(15000 * 6, (5000) * 6);
        //(new Tax())->calc(15000 * 6, (5000) * 6);
        //(new Tax())->calc(15000 * 12, (5000) * 12);

        function reckon($salary, $deduct, $ss, $n = 12)
        {
            $yns = 0;//已纳税
            $a = 0; //总到手收入
            for ($i = 1; $i <= $n; $i++) {
                $tax = (new Tax())->calc($salary * $i, (5000 + $deduct + $ss) * $i); //总纳税
                $na_sui = $tax - $yns;//当月纳税
                $yns = $tax;//已纳税
                $mon = $salary - ($ss + $na_sui); //当月税后收入
                echo $i . '月纳税：' . $na_sui;
                echo PHP_EOL;
                echo $i . '月新水：' . $mon;
                $a += $mon;
                echo PHP_EOL;
                echo PHP_EOL;
            }
            echo '总纳税：' . $yns;
            echo PHP_EOL;
            echo '总收入：' . $a;
        }

        reckon(12500, 1000, 412 + 89, 8);


        /*
        01  11866.85
        02  11842.80
        03  11842.79

        04  11842.79
        05  11842.79
        06  11692.09
        07  11662.08
        08  11812.14

        09  11812.14
        10  11803.84
        11  11807.99
        12  11175.78
         * */


        $arr = [11866.85, 11842.80, 11842.79, 11842.79, 11842.79, 11692.09, 11662.08, 11812.14, 11812.14, 11803.84, 11807.99, 11175.78];
        echo PHP_EOL;
        echo '实际总收入：' . array_sum($arr);
        $this->assertIsString('');
    }
}