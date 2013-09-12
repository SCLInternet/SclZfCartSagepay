<?php

namespace SclZfCartSagepay\Encryption;

use Zend\Crypt\BlockCipher;

/**
 * Encryption class for the sagepay crypt.
 *
 * Manual says it requires AES/CBC/PKCS#5 base64 encoded crypt but that doesn't
 * appear to be completely true.
 *
 * This code is based on this old Sagepay integration kit
 * {@link https://code.google.com/p/sagepay/source/browse/old/2.23/PHPFormKit/includes.php}
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Cipher
{
    const BLOCK_SIZE = 16;

    public function encrypt($data, $password)
    {
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


    public function decrypt($data, $password)
    {
        if (substr($data, 0, 1) != "@") {
            throw new \RuntimeException("@ expected");
        }

        $strIV = $password;

        $data = substr($data, 1);

        $data = pack('H*', $data);

        return mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $password,
            $data,
            MCRYPT_MODE_CBC,
            $strIV
        );
    }

    /**
     * Add PKCS#5 padding.
     */
    private function addPKCS5Padding($data)
    {
        $padding = '';

        // Pad input to an even block size boundary
        $padlength = self::BLOCK_SIZE - (strlen($data) % self::BLOCK_SIZE);

        for ($i = 1; $i <= $padlength; $i++) {
            $padding .= chr($padlength);
        }

        return $data . $padding;
    }
}
