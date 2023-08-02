<?php

use Jaifire\Brisk\Pinyin\ConvertToPinYin;

class ConvertToPinYinTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $t = new ConvertToPinYin();
        $t->separator = ' ';
        $r = $t->convert('大家好');
        var_dump($r);
        $this->assertEquals('da jia hao', $r);
    }
}