<?php
return array(
    'scl_zf_cart_sagepay' => array(
        'live'        => false,
        'name'        => 'Sage Pay - Online credit/debit cart payment',
        'account'     => '',
        'version'     => '3.00',
        'currency' => 'GBP',
        'tx_description'=> 'Online Payment from SclZfCart',
        'live_connection' => array(
            'url' => 'https://live.sagepay.com/gateway/service/vspform-register.vsp',
            'password' => '',
        ),
        'test_connection' => array(
            'url' => 'https://test.sagepay.com/gateway/service/vspform-register.vsp',
            'password' => '',
        ),
    ),
);
