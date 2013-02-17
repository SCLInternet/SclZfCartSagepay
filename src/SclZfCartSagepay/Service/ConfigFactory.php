<?php
namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Data\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see Config} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConfigFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_sagepay';

    /**
     * Create an instance of {@see Config}.
     *
     * @param ServiceLocatorInterface
     * @return Config
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $config = $config[self::CONFIG_KEY];

        return new Config($config);
    }
}
