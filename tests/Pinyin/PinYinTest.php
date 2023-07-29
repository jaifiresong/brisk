<?php

use Exp\Brisk\Pinyin\PinYin;

class PinYinTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $r = PinYin::to_pinyin('宋崇健');
        var_dump($r);
        $this->assertEquals('songchongjian', $r);

    }

    public function testErr()
    {
        echo PinYin::to_pinyin('皞', 'utf-8'); // iconv(): Detected an illegal character in input string
    }
}