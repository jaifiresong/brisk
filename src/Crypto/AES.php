<?php


namespace Jaifire\Brisk\Crypto;
/**
 * base64的特殊字符：+/=
 */
class AES
{
    private $way = 'aes-128-cbc';
    private $key = '123456';
    private $iv = '1234567890654321';

    private function __construct()
    {
        //pass
    }

    public static function summon($way = null, $key = null, $iv = null)
    {
        $instance = new self();
        if ($way) {
            $instance->way = $way;
        }
        if ($key) {
            $instance->key = $key;
        }
        if ($iv) {
            $instance->iv = $iv;
        }
        return $instance;
    }

    public function encrypt($plaintext)
    {
        $ciphertext = base64_encode(openssl_encrypt($plaintext, $this->way, $this->key, true, $this->iv));
        return urlencode($ciphertext);
    }

    public function decrypt($ciphertext)
    {
        $ciphertext = urldecode($ciphertext);
        return openssl_decrypt(base64_decode($ciphertext), $this->way, $this->key, true, $this->iv);
    }


}