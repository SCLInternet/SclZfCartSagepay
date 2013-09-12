<?php

namespace SclZfCartSagepay\Encryption;

class Cipher
{
    const BLOCK_SIZE = 16;

    function encrypt($data, $password)
    {
        $strEncryptionType = "AES";

        $strIV = $password;

        $data = $this->addPKCS5Padding($data);

        $strCrypt = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            $password,
            $data,
            MCRYPT_MODE_CBC,
            $strIV
        );

        return "@" . bin2hex($strCrypt);
    }


    function decrypt($data, $password) {
        if (substr($data, 0, 1) != "@") {
            throw new \RuntimeException("@ expected");
        }

        $strIV = $password;

        $data = substr($data, 1);

        $data = pack('H*', $data);

        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $password, $data, MCRYPT_MODE_CBC, $strIV);
    }

    /**
     * Add PKCS#5 padding.
     */
    private function addPKCS5Padding($data)
    {
        $blocksize = 16;
        $padding = '';

        // Pad input to an even block size boundary
        $padlength = $blocksize - (strlen($data) % self::BLOCK_SIZE);

        for($i = 1; $i <= $padlength; $i++) {
            $padding .= chr($padlength);
        }

        return $data . $padding;
    }

}
