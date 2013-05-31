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
        $options = $serviceLocator->get('SclZfCartSagepay\Options\SagepayOptions');

        $blockCipher = $serviceLocator->get('SclZfCartSagepay\BlockCipher');
        $cryptData = $serviceLocator->get('SclZfCartSagepay\Data\CryptData');

        return new Sagepay($options, $blockCipher, $cryptData);
    }
}
