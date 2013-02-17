<?php

namespace SclZfCartSagepay;

use SclZfCart\Cart;
use SclZfCartPayment\PaymentMethodInterface;
use SclZfCartSagepay\Data\CryptData;
use SclZfCartSagepay\Data\Config;
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
     * @var Config
     */
    private $config;

    /**
     * 
     * @var BlockCipher
     */
    private $blockCipher;

    /**
     * 
     * @var CryptData
     */
    private $cryptData;

    /**
     * @param Config       $provider
     * @param BlockCipher  $blockCipher
     * @param CryptData    $cryptData
     */
    public function __construct(
        Config $config,
        BlockCipher $blockCipher,
        CryptData $cryptData
    ) {
        $this->config = $config;

        $blockCipher->setKey((string) $config->password);
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
        return $this->config->name;
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
     * @param Cart $cart
     * @return string
     */
    private function getCrypt(Cart $cart)
    {
        $this->cryptData
            // @todo Use the SequenceGenerator
            ->add(self::CRYPT_VAR_TX_CODE, 'SCL-TX-')
            // @todo Cart::getAmount()
            ->add(self::CRYPT_VAR_AMOUNT, '')
            ->add(self::CRYPT_VAR_CURRENCY, $this->config->currency)
            ->add(self::CRYPT_VAR_DESCRIPTION, $this->config->transactionDescription)
            // @todo Get urls from routes in the config
            ->add(self::CRYPT_VAR_SUCCESS_URL, '')
            ->add(self::CRYPT_VAR_FAILURE_URL, '');

        $encrypted = $this->blockCipher->encrypt((string) $this->cryptData);

        return base64_encode($encrypted);
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
        $form->setAttribute('action', $this->config->url);

        $this->addHiddenField($form, self::VAR_PROTOCOL, $this->config->version);
        $this->addHiddenField($form, self::VAR_TYPE, self::TX_TYPE_PAYMENT);
        $this->addHiddenField($form, self::VAR_ACCOUNT, $this->config->account);
        $this->addHiddenField($form, self::VAR_CRYPT, $this->getCrypt($cart));
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
