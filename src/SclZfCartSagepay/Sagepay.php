<?php

namespace SclZfCartSagepay;

use SclZfCart\Cart;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCartSagepay\Data\DataProvider;
use Zend\Form\Form;

/**
 * The payment method to intgrate Sagepay into SclZfCartPayment
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Sagepay implements PaymentMethodInterface
{
    const VAR_PROTOCOL = 'VPSProtocol';
    const VAR_TYPE     = 'TxType';
    const VAR_ACCOUNT  = 'Vendor';
    const VAR_CRYPT    = 'Crypt';
    
    const TX_TYPE_PAYMENT = 'PAYMENT';

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
        return $this->provider->getName();
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
     * @param Cart $cart
     * @todo Use a CompleteForm object instead of Form
     */
    public function updateCompleteForm(Form $form, Cart $cart)
    {
        $this->provider->setCart($cart);

        $form->setAttribute('action', $this->provider->url());

        $this->addHiddenField($form, self::VAR_PROTOCOL, $this->provider->version());
        $this->addHiddenField($form, self::VAR_TYPE, self::TX_TYPE_PAYMENT);
        $this->addHiddenField($form, self::VAR_ACCOUNT, $this->provider->account());
        $this->addHiddenField($form, self::VAR_CRYPT, $this->provider->getCrypt());
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
