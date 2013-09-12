<?php

namespace SclZfCartSagepay;

use SclZfCart\Entity\OrderInterface;
use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCartSagepay\Data\CryptData;
use SclZfCartSagepay\Encryption\Cipher;
use SclZfCartSagepay\Options\SagepayOptions;
use SclZfUtilities\Route\UrlBuilder;
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

    const CRYPT_VAR_TX_CODE      = 'VendorTxCode';
    const CRYPT_VAR_AMOUNT       = 'Amount';
    const CRYPT_VAR_CURRENCY     = 'Currency';
    const CRYPT_VAR_DESCRIPTION  = 'Description';
    const CRYPT_VAR_SUCCESS_URL  = 'SuccessURL';
    const CRYPT_VAR_FAILURE_URL  = 'FailureURL';

    /**
     * @var SagepayOptions
     */
    protected $options;

    /**
     *
     * @var Cipher
     */
    protected $cipher;

    /**
     *
     * @var CryptData
     */
    protected $cryptData;

    /**
     * Used to create URLs for the system.
     *
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * @param SagepayOptions $provider
     * @param Cipher         $cipher
     * @param CryptData      $cryptData
     * @param UrlBuilder     $urlBuilder
     */
    public function __construct(
        SagepayOptions $options,
        Cipher $cipher,
        CryptData $cryptData,
        UrlBuilder $urlBuilder
    ) {
        $this->options = $options;

        $this->cipher = $cipher;

        $this->cryptData = $cryptData;

        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name()
    {
        return $this->options->getName();
    }

    /**
     *
     * @param Form $form
     * @param string $name
     * @param string $value
     */
    protected function addHiddenField(Form $form, $name, $value)
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
     * @param  OrderIntefface $order
     * @return string
     */
    protected function getCrypt(OrderInterface $order)
    {
        $this->cryptData
             // @todo Use the SequenceGenerator
             ->add(self::CRYPT_VAR_TX_CODE, 'TEST-SCL-TX-05')
             ->add(self::CRYPT_VAR_AMOUNT, 100) //$order->getTotal())
             ->add(self::CRYPT_VAR_CURRENCY, $this->options->getCurrency())
             ->add(self::CRYPT_VAR_DESCRIPTION, "blah") //$this->options->getTxDescription())
             // @todo Get server name from the environment
             ->add(self::CRYPT_VAR_SUCCESS_URL, 'http://scl.co.uk' . $this->urlBuilder->getUrl('scl-zf-cart-sagepay/success'))
             ->add(self::CRYPT_VAR_FAILURE_URL, 'http://scl.co.uk' . $this->urlBuilder->getUrl('scl-zf-cart-sagepay/failure'))

             // @todo Get this information from the user.
             ->add('BillingSurname', 'Bloggs')
             ->add('BillingFirstnames', 'Joe')
             ->add('BillingAddress1', 'Joes House')
             //->add('BillingAddress2', '')
             ->add('BillingCity', 'Big Town')
             ->add('BillingPostCode', 'SA43 1JD')
             ->add('BillingCountry', 'GB')

             ->add('DeliverySurname', 'Bloggs')
             ->add('DeliveryFirstnames', 'Joe')
             ->add('DeliveryAddress1', 'Joes House')
             //->add('BillingAddress2', '')
             ->add('DeliveryCity', 'Big Town')
             ->add('DeliveryPostCode', 'SA43 1JD')
             ->add('DeliveryCountry', 'GB')
             ;

        return $this->cipher->encrypt(
            (string) $this->cryptData,
            $this->options->getConnectionOptions()->getPassword()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param  Form            $form
     * @param  OrderInterface  $order
     * @param  PaymentInterfce $payment
     * @return void
     * @todo Use a CompleteForm object instead of Form
     */
    public function updateCompleteForm(Form $form, OrderInterface $order, PaymentInterface $payment)
    {
        $form->setAttribute('action', $this->options->getConnectionOptions()->getUrl());

        $this->addHiddenField($form, self::VAR_PROTOCOL, $this->options->getVersion());
        $this->addHiddenField($form, self::VAR_TYPE, self::TX_TYPE_PAYMENT);
        $this->addHiddenField($form, self::VAR_ACCOUNT, $this->options->getAccount());
        $this->addHiddenField($form, self::VAR_CRYPT, $this->getCrypt($order));
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
