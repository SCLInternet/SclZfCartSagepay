<?php

namespace SclZfCartSagepay;

use SclZfCartPayment\PaymentMethodInterface;
use Zend\Form\Form;

/**
 * The payment method to intgrate Sagepay into SclZfCartPayment
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Sagepay implements PaymentMethodInterface
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
     * 
     * @param Form $form
     * @param string $name
     * @param string $value
     */
    private function addHiddenField(Form $form, $name, $value)
    {
        $form->add(
            array(
                'name' => $name,
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                    'value' => $value
                )
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param Form $form
     */
    public function updateCompleteForm(Form $form)
    {
        $form->setAttribute('action', 'sagepay_url');

        $this->addHiddenField($form, 'VPSProtocol', '3.00'); 
        $this->addHiddenField($form, 'TxType', 'PAYMENT');
        $this->addHiddenField($form, 'Vendor Name', 'asfg');
        $this->addHiddenField($form, 'Crypt', 'secretshit');
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
