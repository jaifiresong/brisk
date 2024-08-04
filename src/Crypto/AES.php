<?php


namespace Jaifire\Brisk\Crypto;


/**
 * base64的特殊字符：+/=
 */
class AES
{
    private $way = 'aes-128-cbc';
    private $key;
    private $iv;

    private function __construct($key)
    {
        $this->key = $key;
    }

    public static function summon($key, $way = null): AES
    {
        $instance = new self($key);
        if ($way) {
            $instance->way = $way;
        }
        $instance->iv = substr(base64_encode($key), 0, 16);
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