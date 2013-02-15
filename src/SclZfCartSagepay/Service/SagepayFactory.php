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
    /**
     * Create an instance of {@see Sagepay}.
     *
     * @param ServiceLocatorInterface
     * @return Sagepay
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $provider = $serviceLocator->get('SclZfCartSagepay\DataProvider');

        return new Sagepay($provider);
    }
}
