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
     * @param array $config
     */
    public function __construct(array $config, BlockCipher $blockCipher, CryptData $cryptData)
    {
        $this->name = (string) $config[self::CONFIG_NAME];

        $this->version = (string) $config[self::CONFIG_VERSION];

        $this->vspAccount = (string) $config[self::CONFIG_VSP_ACCOUNT];

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 
     * @return string
     */
    public function getAccount()
    {
        return $this->vspAccount;
    }

    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @return string
     */
    public function getCrypt()
    {
        $this->cryptData->add('field', 'value');

        $encrypted = $this->blockCipher->encrypt((string) $this->cryptData);

        return base64_encode($encrypted);
    }
}
