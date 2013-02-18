<?php 

namespace SclZfCartSagepay;

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
        return array();
    }

    public function failureAction()
    {
        return array();
    }
}
