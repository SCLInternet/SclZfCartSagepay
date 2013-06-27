<?php

namespace SclZfCartSagepay;

use SclZfCart\Entity\OrderInterface;
use SclZfCartPayment\Entity\PaymentInterface;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCartSagepay\Data\CryptData;
use SclZfCartSagepay\Options\SagepayOptions;
use Zend\Crypt\BlockCipher;
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
     * @var BlockCipher
     */
    protected $blockCipher;

    /**
     *
     * @var CryptData
     */
    protected $cryptData;

    /**
     * @param SagepayOptions $provider
     * @param BlockCipher    $blockCipher
     * @param CryptData      $cryptData
     */
    public function __construct(
        SagepayOptions $options,
        BlockCipher $blockCipher,
        CryptData $cryptData
    ) {
        $this->options = $options;

        $blockCipher->setKey((string) $options->getConnectionOptions()->getPassword());
        $this->blockCipher = $blockCipher;

        $this->cryptData = $cryptData;
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
             ->add(self::CRYPT_VAR_TX_CODE, 'SCL-TX-')
             // @todo Cart::getAmount()
             ->add(self::CRYPT_VAR_AMOUNT, '')
             ->add(self::CRYPT_VAR_CURRENCY, $this->options->getCurrency())
             ->add(self::CRYPT_VAR_DESCRIPTION, $this->options->getTxDescription())
             // @todo Get urls from routes in the options
             ->add(self::CRYPT_VAR_SUCCESS_URL, '')
             ->add(self::CRYPT_VAR_FAILURE_URL, '');

        $encrypted = $this->blockCipher->encrypt((string) $this->cryptData);

        return base64_encode($encrypted);
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
