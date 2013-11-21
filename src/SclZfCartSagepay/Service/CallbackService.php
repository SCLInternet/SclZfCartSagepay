<?php

namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Encryption\Cipher;
use SclZfCartSagepay\Options\ConnectionOptions;
use SclZfCartSagepay\Service\CryptService;

class CallbackService
{
    private $cipher;

    private $options;

    private $cryptService;

    /**
     * Set collaborator objects.
     *
     * @param  Cipher $cipher
     * @param  ConnectionOptions $options
     * @param  CryptService $cryptService
     */
    public function __construct(
        Cipher $cipher,
        ConnectionOptions $options,
        CryptService $cryptService
    ) {
        $this->cipher       = $cipher;
        $this->options      = $options;
        $this->cryptService = $cryptService;
    }

    public function processResponse($encryptedData)
    {
        $data = $this->cipher->decrypt($encryptedData, $this->options->getPassword());

        var_dump($this->cryptService->processResponseData($data));
    }
}
