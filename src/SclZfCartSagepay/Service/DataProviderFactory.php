<?php
namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Data\DataProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see DataProvider} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DataProviderFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_sagepay';

    /**
     * Create an instance of {@see DataProvider}.
     *
     * @param ServiceLocatorInterface
     * @return DataProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $config = $config[self::CONFIG_KEY];

        return new DataProvider($config);
    }
}
