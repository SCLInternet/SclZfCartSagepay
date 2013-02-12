<?php

namespace SclZfCartSagepay;

use SclZfCartPayment\PaymentSystemInterface;

/**
 * The payment systems to intgrate Sagepay into SclZfCartPayment
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Sagepay implements PaymentSystemInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name()
    {
        return 'Sage Pay - Online credit/debit cart payment';
    }

    /**
     * {@inheritDoc}
     *
     * @param Form $form
     */
    public function updateCompleteForm(Form $form)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @param array $data
     * @return boolean Return true if the payment was successful
     */
    public function complete(array $data)
    {
    }
}