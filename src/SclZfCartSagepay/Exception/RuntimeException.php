<?php

namespace SclZfCartSagepay\Exception;

use SclZfCartPayment\Entity\PaymentInterface;

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
     * @param  PaymentInterface $payment
     *
     * @return RuntimeException
     */
    public static function paymentHasCompleted(PaymentInterface $payment)
    {
        return new self(sprintf(
            'Payment "%s" has already been actioned.',
            $payment->getTransactionId()
        ));
    }
}
