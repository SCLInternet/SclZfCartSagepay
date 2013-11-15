<?php

namespace SclZfCartSagepay\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Provides the call back pages for sagepay to notify of the status of
 * a transaction.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class PaymentController extends AbstractActionController
{
    public function successAction()
    {
        $crypt = $this->getRequest()->getQuery('crypt');

        $serviceLocator = $this->getServiceLocator();

        $cipher = $serviceLocator->get('SclZfCartSagepay\Encryption\Cipher');
        $options = $serviceLocator->get('SclZfCartSagepay\Options\SagepayOptions');

        $cryptService = $serviceLocator->get('SclZfCartSagepay\Service\CryptService');

        $data = $cipher->decrypt(
            $crypt,
            $options->getConnectionOptions()->getPassword()
        );

        var_dump($cryptService->processResponseData($data));

        return [];
    }

    public function failureAction()
    {
        return [];
    }
}
