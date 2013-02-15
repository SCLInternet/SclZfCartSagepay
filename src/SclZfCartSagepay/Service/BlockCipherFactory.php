<?php
namespace SclZfCartSagepay\Service;

use Zend\Crypt\BlockCipher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating the Zend BlockCipher which can use used for encryption.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class BlockCipherFactory implements FactoryInterface
{
    /**
     * Creates a BlockCipher
     *
     * @param ServiceLocatorInterface
     * @return BlockCipher
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return BlockCipher::factory(
            'mcrypt',
            array('algo' => 'aes')
        );
    }
}
