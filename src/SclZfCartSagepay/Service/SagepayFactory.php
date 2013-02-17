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
        $provider = $serviceLocator->get('SclZfCartSagepay\Data\DataProvider');

        $blockCipher = $serviceLocator->get('SclZfCartSagepay\BlockCipher');
        $cryptData = $serviceLocator->get('SclZfCartSagepay\Data\CryptData');

        try {
            $sp = new Sagepay($provider, $blockCipher, $cryptData);
        } catch (\Exception $e) {
            die(get_class($e) . $e->getMessage());
        }
        
        return new Sagepay($provider, $blockCipher, $cryptData);
    }
}
