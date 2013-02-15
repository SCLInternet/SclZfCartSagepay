<?php
namespace SclZfCartSagepay\Service;

use SclZfCartSagepay\Sagepay;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating {@see Sagepay} objects.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class SagepayFactory implements FactoryInterface
{
    const CONFIG_KEY = 'scl_zf_cart_sagepay';

    /**
     * Create an instance of {@see Sagepay}.
     *
     * @param ServiceLocatorInterface
     * @return Sagepay
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $config = $config[self::CONFIG_KEY];

        return new Sagepay($config);
    }
}
