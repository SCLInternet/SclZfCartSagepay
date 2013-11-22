<?php

namespace SclZfCartSagepay;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * This module provides them implementation for using Sage Pay with
 * SclZfCartPayement
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'SclZfCartSagepay\Service\CryptService' => 'SclZfCartSagepay\Service\CryptService',
                'SclZfCartSagepay\Encryption\Cipher'    => 'SclZfCartSagepay\Encryption\Cipher',
            ],
            'factories' => [
                'SclZfCartSagepay\Sagepay'                => 'SclZfCartSagepay\Service\SagepayFactory',
                'SclZfCartSagepay\Options\SagepayOptions' => 'SclZfCartSagepay\Service\SagepayOptionsFactory',
                'SclZfCartSagepay\Service\CallbackService' => function ($sm) {
                    return new \SclZfCartSagepay\Service\CallbackService(
                        $sm->get('SclZfCartSagepay\Encryption\Cipher'),
                        $sm->get('SclZfCartSagepay\Options\SagepayOptions')->getConnectionOptions(),
                        $sm->get('SclZfCartSagepay\Service\CryptService'),
                        $sm->get('SclZfCartPayment\Mapper\PaymentMapperInterface'),
                        $sm->get('SclZfCartPayment\Service\PaymentService')
                    );
                },
            ],
        ];
    }
}
