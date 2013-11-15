<?php

return array(
    'modules' => array(
        'ZfcUser',
        'DoctrineModule',
        'DoctrineORMModule',
        'SclZfUtilities',
        'SclZfSequenceGenerator',
        'SclZfGenericMapper',
        'SclZfCart',
        'SclZfCartPayment',
        'SclZfCartSagepay',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            __DIR__ . '/config.php',
        ),
        'module_paths' => array(
            __DIR__ . '/../..',
            __DIR__ . '/../vendor',
        ),
    ),
);
