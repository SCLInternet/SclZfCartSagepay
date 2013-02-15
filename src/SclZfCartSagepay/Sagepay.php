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
     * @var DataProvider
     */
    private $provider;

    /**
     * @param DataProvider $provider
     */
    public function __construct(DataProvider $provider)
    {
        $this->provider = $provider;
    }

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
     * @todo Use a CompleteForm object instead of Form
     */
    public function updateCompleteForm(Form $form)
    {
        $form->setAttribute('action', $this->provider->getUrl());

        $this->addHiddenField($form, 'VPSProtocol', $this->provider->getVersion()); 
        $this->addHiddenField($form, 'TxType', 'PAYMENT');
        $this->addHiddenField($form, 'Vendor Name', $this->provider->getAccount());
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
