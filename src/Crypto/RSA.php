<?php


namespace Jaifire\Brisk\Crypto;


class RSA
{
    public static function encrypt($plaintext, $public_key): array
    {
        $crypto = [];
        //RSA最大加密长度是117字节
        foreach (str_split($plaintext, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, openssl_get_publickey($public_key));
            $crypto[] = base64_encode($encryptData);
        }
        return $crypto;
    }

    public static function decrypt($text, $private_key): string
    {
        if (!is_array($text)) {
            $text = [$text];
        }
        $data = [];
        foreach ($text as $ciphertext) {
            openssl_private_decrypt(base64_decode($ciphertext), $decrypted, openssl_get_privatekey($private_key));
            $data[] = $decrypted;
        }
        return implode('', $data);
    }

    public static function sign($message, $private_key)
    {
        $success = openssl_sign($message, $signature, openssl_get_privatekey($private_key), OPENSSL_ALGO_SHA256);
        if (!$success) {
            trigger_error(openssl_error_string(), E_USER_ERROR);
        }
        return base64_encode($signature);
    }

    public static function sign_verify($message, $signature, $public_key, &$errInfo = null)
    {
        $success = openssl_verify($message, base64_decode($signature), openssl_get_publickey($public_key), OPENSSL_ALGO_SHA256);
        if (1 === $success) {
            return true;
        }
        while ($err = openssl_error_string()) {
            $errInfo[] = $err;
        }
        return false;
    }
}