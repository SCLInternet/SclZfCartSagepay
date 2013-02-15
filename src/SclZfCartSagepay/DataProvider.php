<?php

namespace SclZfCartSagepay;

class DataProvider
{
    const CONFIG_VERSION       = 'version';
    const CONFIG_LIVE          = 'live';
    const CONFIG_VSP_ACCOUNT   = 'vsp_account';
    const CONFIG_CONNECTION    = 'connection';
    const CONFIG_LIVE_SETTINGS = 'live';
    const CONFIG_TEST_SETTINGS = 'test';
    const CONFIG_URL           = 'url';
    const CONFIG_PASSWORD      = 'encryption_password';

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
    private $encryptionPassword;

    /**
     * 
     * @var string
     */
    private $url;

    /**
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->version = (string) $config[self::CONFIG_VERSION];
        $this->vspAccount = (string) $config[self::CONFIG_VSP_ACCOUNT];

        if ($config[self::CONFIG_LIVE]) {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_LIVE_SETTINGS];
        } else {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_TEST_SETTINGS];
        }

        $this->encryptionPassword = (string) $settings[self::CONFIG_PASSWORD];
        $this->url = (string) $settings[self::CONFIG_URL];
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
     * @return string
     */
    public function getCrypt()
    {
        
    }
}
