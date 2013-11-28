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

        $service = $serviceLocator->get('SclZfCartSagepay\Service\CallbackService');

        $payment = $service->processResponse($crypt);

        return $this->redirect()->toRoute(
            'cart/checkout/complete',
            ['id' => $payment->getOrder()->getId()]
        );
    }

    public function failureAction()
    {
        return [];
    }
}
