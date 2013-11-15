<?php

return [
    'service_manager' => [
        'aliases' => [
            'SclZfCart\Customer\CustomerLocatorInterface' => 'test_customer_locator',
        ],
        'invokables' => [
            'test_customer_locator' => 'SclZfCartSagepayTests\TestAssets\TestCustomerLocator',
        ],
    ],
];
