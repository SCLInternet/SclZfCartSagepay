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
    private $account;

    /**
     * 
     * @var string
     */
    private $password;

    /**
     * 
     * @var string
     */
    private $url;


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
    public function __construct(array $config)
    {
        $this->name = (string) $config[self::CONFIG_NAME];

        $this->version = (string) $config[self::CONFIG_VERSION];

        $this->account = (string) $config[self::CONFIG_VSP_ACCOUNT];

        $this->currency = (string) $config[self::CONFIG_TX_CURRENCY];

        $this->transactionDescription = (string) $config[self::CONFIG_TX_DESCRIPTION];

        if ($config[self::CONFIG_LIVE]) {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_LIVE_SETTINGS];
        } else {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_TEST_SETTINGS];
        }

        $this->url = (string) $settings[self::CONFIG_URL];
        $this->password =  (string) $settings[self::CONFIG_PASSWORD];
    }

    /**
     * Make this class read only
     *
     * @param string $name
     * @param mixed  $value
     * @throws \Exception
     * @todo Throw a proper exception
     */
    public function __set($name, $value)
    {
        throw new \Exception('This object is read only');
    }

    /**
     * Expose private memebers
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     * @todo Throw a proper exception
     */
    public function __get($name)
    {
        if (!isset($this->$name)) {
            throw new \Exception("Unknown property '$name'");
        }

        return $this->$name;
    }
}
