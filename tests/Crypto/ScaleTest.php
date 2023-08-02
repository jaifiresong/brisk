<?php

use Jaifire\Brisk\Crypto\Scale;

class ScaleTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $str = 'ScaleTest';
        $r = Scale::to_scale16($str);
        var_dump($r);
        $this->assertEquals(Scale::decode_scale16($r), $str);
    }
}