<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

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
            'config.php',
        ),
        'module_paths' => array(
            __DIR__ . '/../..',
            __DIR__ . '/../vendor',
        ),
    ),
);
