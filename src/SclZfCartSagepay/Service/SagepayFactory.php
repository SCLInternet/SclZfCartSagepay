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
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Sagepay
     * @todo Handle exceptions from when Sagepay is created.
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Sagepay(
            $serviceLocator->get('SclZfCartSagepay\Options\SagepayOptions'),
            $serviceLocator->get('SclZfCartSagepay\Encryption\Cipher'),
            $serviceLocator->get('SclZfCartSagepay\Data\CryptData'),
            $serviceLocator->get('SclZfUtilities\Route\UrlBuilder')
        );
    }
}
