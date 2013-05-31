<?php
namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Options\SagepayOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see SagepayConfig} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class SagepayOptionsFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_sagepay';

    /**
     * Create an instance of {@see SagepayOptions}.
     *
     * @param  ServiceLocatorInterface
     * @return SagepayOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $config = $config[self::CONFIG_KEY];

        return new SagepayOptions($config);
    }
}
