<?php

namespace SclZfCartSagepay\Data;

use SclZfCart\Cart;
use Zend\Crypt\BlockCipher;

/**
 * Collects up the data that is needed for Sagepay and formats it as required.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DataProvider
{
    const CONFIG_NAME          = 'name';
    const CONFIG_VERSION       = 'version';
    const CONFIG_TX_CURRENCY   = 'tx_currency';
    const CONFIG_TX_DESCRIPTION= 'tx_description';
    const CONFIG_LIVE          = 'live';
    const CONFIG_VSP_ACCOUNT   = 'vsp_account';
    const CONFIG_CONNECTION    = 'connection';
    const CONFIG_LIVE_SETTINGS = 'live';
    const CONFIG_TEST_SETTINGS = 'test';
    const CONFIG_URL           = 'url';
    const CONFIG_PASSWORD      = 'encryption_password';

    /**
     * The name of this payment method.
     * 
     * @var string
     */
    private $name;

    /**
     * The sagepay protocol version
     *
     * @var string
     */
    private $version;

    /**
     * 
     * @var string
     */
    private $vspAccount;

    /**
     * 
     * @var string
     */
    private $url;

    /**
     * 
     * @var Cart
     */
    private $cart;

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
     *
     * @var string
     */
    private $currency;

    /**
     * 
     * @var string
     */
    private $transactionDescription;

    /**
     * 
     * @param array $config
     */
    public function __construct(array $config, BlockCipher $blockCipher, CryptData $cryptData)
    {
        $this->name = (string) $config[self::CONFIG_NAME];

        $this->version = (string) $config[self::CONFIG_VERSION];

        $this->vspAccount = (string) $config[self::CONFIG_VSP_ACCOUNT];

        $this->currency = (string) $config[self::CONFIG_CURRENCY];

        $this->transactionDescription = (string) $config[self::CONFIG_TX_DESCRIPTION];

        if ($config[self::CONFIG_LIVE]) {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_LIVE_SETTINGS];
        } else {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_TEST_SETTINGS];
        }

        $this->url = (string) $settings[self::CONFIG_URL];

        $blockCipher->setKey((string) $settings[self::CONFIG_PASSWORD]);

        $this->blockCipher = $blockCipher;
        $this->cryptData = $cryptData;
    }

    /**
     *
     * @param Cart $cart
     */
    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * 
     * @return string
     */
    public function account()
    {
        return $this->vspAccount;
    }

    /**
     * 
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    public function currency()
    {
        return $this->currency;
    }

    public function transactionDescription()
    {
        return $this->transactionDescription;
    }

    const CRYPT_VAR_TX_CODE      = 'VendorTxCode';
    const CRYPT_VAR_AMOUNT       = 'Amount';
    const CRYPT_VAR_CURRENCY     = 'Currency';
    const CRYPT_VAR_DESCRIPTION  = 'Description';

    /**
     *
     * @return string
     */
    public function getCrypt()
    {
        $this->cryptData
            // @todo Use the SequenceGenerator
            ->add(self::CRYPT_VAR_TX_CODE, 'SCL-TX-')
            // @todo Cart::getAmount()
            ->add(self::CRYPT_VAR_AMOUNT, '')
            ->add(self::CRYPT_VAR_CURRENCY, $this->currency)
            ->add(self::CRYPT_VAR_DESCRIPTION, $this->transactionDescription);

        $encrypted = $this->blockCipher->encrypt((string) $this->cryptData);

        return base64_encode($encrypted);
    }
}
