<?php


namespace Support\Utility;

use RuntimeException;

class Hasher
{
    private $cipher = 'AES-256-CTR',
            $phrase = '';

    public function __construct()
    {

    }

    public function encrypt($data)
    {
        $this->iv = openssl_random_pseudo_bytes(
            openssl_cipher_iv_length($this->cipher)
        );
        return openssl_encrypt($data, $this->cipher, $this->phrase, $options = 0, $this->iv);
    }

    public function decrypt($data)
    {
        return openssl_decrypt($data, $this->cipher, $this->phrase, $options = 0, $this->iv);
    }
}
?>