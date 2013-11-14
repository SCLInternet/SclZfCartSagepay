<?php

namespace SclZfCartSagepay;

use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCartSagepay\Data\CryptData;
use SclZfCartSagepay\Encryption\Cipher;
use SclZfCartSagepay\Options\SagepayOptions;
use SclZfCart\Customer\CustomerInterface;
use SclZfCart\Entity\OrderInterface;
use SclZfSequenceGenerator\SequenceGeneratorInterface;
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
    private $options;

    /**
     *
     * @var Cipher
     */
    private $cipher;

    /**
     *
     * @var CryptData
     */
    private $cryptData;

    /**
     * Used to create URLs for the system.
     *
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Used to get numbers in a sequence.
     *
     * @var SequenceGeneratorInterface
     */
    private $sequenceGenerator;

    /**
     * The customer object
     *
     * @var Customer
     */
    private $customer;

    /**
     * @param SagepayOptions             $provider
     * @param Cipher                     $cipher
     * @param CryptData                  $cryptData
     * @param UrlBuilder                 $urlBuilder
     * @param SequenceGeneratorInterface $sequenceGenerator
     * @param CustomerInterface          $customer
     */
    public function __construct(
        SagepayOptions             $options,
        Cipher                     $cipher,
        CryptData                  $cryptData,
        UrlBuilder                 $urlBuilder,
        SequenceGeneratorInterface $sequenceGenerator,
        CustomerInterface          $customer
    ) {
        $this->options           = $options;
        $this->cipher            = $cipher;
        $this->cryptData         = $cryptData;
        $this->urlBuilder        = $urlBuilder;
        $this->sequenceGenerator = $sequenceGenerator;
        $this->customer          = $customer;
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

    private function addContact(CryptData $cryptData, $prefix, CustomerInterface $customer)
    {
        $contact = $customer->getContact();
        $address = $contact->getAddress();

        $cryptData->add($prefix . 'Surname', $contact->getName()->getLastName());
        $cryptData->add($prefix . 'Firstnames', $contact->getName()->getFirstName());
        $cryptData->add($prefix . 'Address1', $address->getLine1());
        $cryptData->add($prefix . 'Address2', $address->getLine2());
        $cryptData->add($prefix . 'City', $address->getCity());
        $cryptData->add($prefix . 'PostCode', $address->getPostCode()->get());
        $cryptData->add($prefix . 'Country', $address->getCountry()->getCode());
    }

    /**
     * @param  OrderIntefface $order
     * @return string
     */
    private function getCrypt(OrderInterface $order)
    {
        $this->cryptData
             // @todo Use the SequenceGenerator
             ->add(self::CRYPT_VAR_TX_CODE, $this->getTransactionId())
             ->add(self::CRYPT_VAR_AMOUNT, $order->getTotal())
             ->add(self::CRYPT_VAR_CURRENCY, $this->options->getCurrency())
             ->add(self::CRYPT_VAR_DESCRIPTION, "blah") //$this->options->getTxDescription())
             // @todo Get server name from the environment
             ->add(self::CRYPT_VAR_SUCCESS_URL, $this->getCallbackUrl('success'))
             ->add(self::CRYPT_VAR_FAILURE_URL, $this->getCallbackUrl('failure'))
             ;

        $this->addContact($this->cryptData, 'Billing', $this->customer);
        $this->addContact($this->cryptData, 'Delivery', $this->customer);

        return $this->cipher->encrypt(
            (string) $this->cryptData,
            $this->options->getConnectionOptions()->getPassword()
        );
    }

    /**
     * getTransactionId
     *
     * @return string
     */
    private function getTransactionId()
    {
        return 'TEST-SCL-TX-' . $this->sequenceGenerator->get('SAGEPAY-PAYMENT-TX-ID');
    }

    /**
     * getCallbackUrl
     *
     * @param  string $type
     * @return string
     */
    private function getCallbackUrl($type)
    {
        return 'http://scl.co.uk' . $this->urlBuilder->getUrl('scl-zf-cart-sagepay/' . $type);
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
