<?php

namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Encryption\Cipher;
use SclZfCartSagepay\Exception\RuntimeException;
use SclZfCartSagepay\Options\ConnectionOptions;
use SclZfCartSagepay\Service\CryptService;
use SclZfCartPayment\Mapper\PaymentMapperInterface;
use SclZfCartPayment\Service\PaymentService;

class CallbackService
{
    private $cipher;

    private $options;

    private $cryptService;

    private $paymentMapper;

    /**
     * Set collaborator objects.
     *
     * @param  Cipher                 $cipher
     * @param  ConnectionOptions      $options
     * @param  CryptService           $cryptService
     * @param  PaymentMapperInterface $paymentMapper
     */
    public function __construct(
        Cipher $cipher,
        ConnectionOptions $options,
        CryptService $cryptService,
        PaymentMapperInterface $paymentMapper,
        PaymentService $paymentService
    ) {
        $this->cipher         = $cipher;
        $this->options        = $options;
        $this->cryptService   = $cryptService;
        $this->paymentMapper  = $paymentMapper;
        $this->paymentService = $paymentService;
    }

    public function processResponse($encryptedData)
    {
        $data = $this->cipher->decrypt($encryptedData, $this->options->getPassword());

        $response = $this->cryptService->processResponseData($data);

        $payment = $this->paymentMapper->findByTransactionId($response->vendorTxCode);

        if (!$payment) {
            throw RuntimeException::paymentDoesNotExist($response->vendorTxCode);
        }

        if ($this->paymentService->isComplete($payment)) {
            throw RuntimeException::paymentHasCompleted($payment);
        }

        // @todo save sagepay information

        if ($response->isSuccess()) {
            $this->paymentService->complete($payment);
        } else {
            $this->paymentService->fail($payment);
        }

        return $payment;
    }
}
