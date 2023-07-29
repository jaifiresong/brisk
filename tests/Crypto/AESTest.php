<?php

use Exp\Brisk\Crypto\AES;

class AESTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $str = '\Exp\Brisk\Crypto';
        $ciphertext = AES::summon()->encrypt($str);
        var_dump($ciphertext);
        $plaintext = AES::summon()->decrypt($ciphertext);
        $this->assertEquals($plaintext, $str);
    }
}