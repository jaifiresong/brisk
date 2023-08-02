<?php


namespace Test\Crypto;


use Exp\Brisk\Crypto\RSA;
use PHPUnit\Framework\TestCase;

class RSATest extends TestCase
{
    public function test01()
    {
        //openssl_get_privatekey 和 openssl_pkey_get_private 都用于获取私钥，前者在失败时会抛出错误，后者则返回 false。
        $pub = file_get_contents(__DIR__ . '/cert/public_key.pem');
        $pub = openssl_get_publickey($pub);
        var_dump($pub);

        $pri = file_get_contents(__DIR__ . '/cert/private_key.pem');
        $pri = openssl_pkey_get_private($pri);
        var_dump($pri);
        $this->assertIsString('');
    }

    public function test02()
    {
        $pub = file_get_contents(__DIR__ . '/cert/public_key.pem');
        $pri = file_get_contents(__DIR__ . '/cert/private_key.pem');
        $txt = '未未未未未未未未未未';
        $txt .= '担担担担担担担担担担';
        $txt .= '任任任任任任任任任任';
        $txt .= '未未未未未未未未未未';
        $txt .= '担担担担担担担担担担';
        $txt .= '任任任任任任任任任任';
        $txt .= '未未未未未未未未未未';
        $txt .= '担担担担担担担担担担';
        $txt .= '任任任任任任任任任任';
        $txt .= '未未未未未未未未未未';
        $txt .= '担担担担担担担担担担';
        $txt .= '任任任任任任任任任任';
        $txt .= '未未未未未未未未未未';
        $txt .= '担担担担担担担担担担';
        $txt .= '任任任任任任任任任任';

        $arr = RSA::encrypt($txt, $pub);
        print_r($arr);

        $plaintext = RSA::decrypt($arr, $pri);
        var_dump($plaintext);
        $this->assertIsString('');
    }

    public function test03()
    {
        $pub = file_get_contents(__DIR__ . '/cert/public_key.pem');
        $pri = file_get_contents(__DIR__ . '/cert/private_key.pem');

        $msg = 'PHPUnit 8.5.33 by Sebastian Bergmann and contributors.';
        $sign = RSA::sign($msg, $pri);
        var_dump($sign);
        $rst = RSA::sign_verify($msg, $sign, $pub, $info);
        var_dump($rst);
        var_dump($info);
        $this->assertIsString('');
    }
}