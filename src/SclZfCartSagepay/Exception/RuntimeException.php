<?php

namespace SclZfCartSagepay\Exception;

use SclZfCartPayment\Entity\Payment;

class RuntimeException extends \RuntimeException implements
    ExceptionInterface
{
    /**
     * 'Payment with transaction ID "%s" does not exist.'
     *
     * @param  string $transactionId
     *
     * @return RuntimeException
     */
    public static function paymentDoesNotExist($transactionId)
    {
        return new self(sprintf(
            'Payment with transaction ID "%s" does not exist.',
            $transactionId
        ));
    }

    /**
     * 'Payment "%s" has already been actioned.'
     *
     * @return RuntimeException
     */
    public static function paymentHasCompleted(Payment $payment)
    {
        return new self(sprintf(
            'Payment "%s" has already been actioned.',
            $payment->getTransactionId()
        ));
    }
}
