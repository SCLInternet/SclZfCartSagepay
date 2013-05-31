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
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
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
        return array(
            'invokables' => array(
                'SclZfCartSagepay\Data\CryptData' => 'SclZfCartSagepay\Data\CryptData',
            ),
            'factories' => array(
                'SclZfCartSagepay\BlockCipher'            => 'SclZfCartSagepay\Service\BlockCipherFactory',
                'SclZfCartSagepay\Sagepay'                => 'SclZfCartSagepay\Service\SagepayFactory',
                'SclZfCartSagepay\Options\SagepayOptions' => 'SclZfCartSagepay\Service\SagepayOptionsFactory',
            ),
        );
    }
}
