<?php

namespace Test\Crypto;

use Jaifire\Brisk\Crypto\AES;
use PHPUnit\Framework\TestCase;

class AESTest extends TestCase
{
    public function test()
    {
        $text = 'use PHPUnit\Framework\TestCase;';
        $key = '2FOwQc4R52b2uf6PDUtn5rI1aiWfrheew';
        $s = AES::summon($key)->encrypt($text);
        var_dump($s);
        $s = AES::summon($key)->decrypt($s);
        var_dump($s);
        $this->assertEquals($text, $s);
    }
}