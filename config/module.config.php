<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'SclZfCartSagepay\Controller\Payment' => 'SclZfCartSagepay\Controller\PaymentController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'scl-zf-cart-sagepay' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sagepay-callback',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'success' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/success',
                            'defaults' => array(
                                'controller' => 'SclZfCartSagepay\Controller\Payment',
                                'action'     => 'success',
                            ),
                        ),
                    ),
                    'failure' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/failure',
                            'defaults' => array(
                                'controller' => 'SclZfCartSagepay\Controller\Payment',
                                'action'     => 'failure',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

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
