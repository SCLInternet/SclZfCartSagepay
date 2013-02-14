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

        if ($config[self::LIVE]) {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_LIVE];
        } else {
            $settings = $config[self::CONFIG_CONNECTION][self::CONFIG_TEST];
        }

        $this->encryptionPassword = (string) $settings[self::CONFIG_PASSWORD];
        $this->url = (string) $settings[self::CONFIG_URL]; 
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
     */
    public function updateCompleteForm(Form $form)
    {
        $form->setAttribute('action', 'sagepay_url');

        $this->addHiddenField($form, 'VPSProtocol', '3.00'); 
        $this->addHiddenField($form, 'TxType', 'PAYMENT');
        $this->addHiddenField($form, 'Vendor Name', 'asfg');
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
