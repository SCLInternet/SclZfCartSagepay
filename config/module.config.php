<?php
return array(
    'scl_zf_cart_sagepay' => array(
        'live'        => false,
        'name'        => 'Sage Pay - Online credit/debit cart payment',
        'vsp_account' => '',
        'version'     => '3.00',
        'connection'  => array(
            'live' => array(
                'url' => 'https://live.sagepay.com/gateway/service/vspform-register.vsp',
                'encryption_password' => '',
            ),
            'test' => array(
                'url' => 'https://test.sagepay.com/gateway/service/vspform-register.vsp',
                'encryption_password' => '',
            ),
        ),
    ),
);