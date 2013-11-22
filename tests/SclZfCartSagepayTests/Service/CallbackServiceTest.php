<?php

namespace SclZfCartSagepayTests\Service;

use SclZfCartSagepay\Service\CallbackService;
use SclZfCartSagepay\Options\ConnectionOptions;
use SclZfCartPayment\Service\PaymentService;
use SclZfCartSagepay\Service\CryptService;
use SclZfCartPayment\Entity\Payment;

/**
 * Unit tests for {@see CallbackService}.
 *
 * @covers SclZfCartSagepay\Service\CallbackService
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CallbackServiceTest extends \PHPUnit_Framework_TestCase
{
    const TEST_PASSWORD = 'abracadabra';

    private $service;

    private $cipher;

    private $cryptService;

    private $paymentMapper;

    private $paymentService;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->cipher = $this->getMock('SclZfCartSagepay\Encryption\Cipher');

        $this->cryptService = $this->getMock('SclZfCartSagepay\Service\CryptService');

        $this->paymentMapper = $this->getMock('SclZfCartPayment\Mapper\PaymentMapperInterface');

        $this->paymentService = $this->getMockBuilder('SclZfCartPayment\Service\PaymentService')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->service = new CallbackService(
            $this->cipher,
            $this->createOptions(),
            $this->cryptService,
            $this->paymentMapper,
            $this->paymentService
        );
    }

    public function test_service_manager_creates_instance()
    {
        $this->assertInstanceOf(
            'SclZfCartSagepay\Service\CallbackService',
            \TestBootstrap::getApplication()
                          ->getServiceManager()
                          ->get('SclZfCartSagepay\Service\CallbackService')
        );
    }

    public function test_processResponse_decrypts_encrypted_data()
    {
        $data = 'encrypted_data';

        $this->cipher
            ->expects($this->once())
            ->method('decrypt')
            ->with($this->equalTo($data), $this->equalTo(self::TEST_PASSWORD));

        $this->makeCryptServiceReturnResponse();
        $this->makeMapperReturnPayment(new Payment());

        $this->service->processResponse($data);
    }

    public function test_processResponse_processes_decrypted_data()
    {
        $data = 'unencrypted_data';

        $this->cipher
             ->expects($this->any())
             ->method('decrypt')
             ->will($this->returnValue($data));

        $this->cryptService
             ->expects($this->once())
             ->method('processResponseData')
             ->with($this->equalTo($data))
             ->will($this->returnValue($this->createResponse()));

        $this->makeMapperReturnPayment(new Payment());

        $this->service->processResponse($data);
    }

    public function test_processResponse_loads_payment_from_transaction_id()
    {
        $transactionId = 'TX123';

        $this->makeCryptServiceReturnResponse("VendorTxCode=$transactionId");

        $this->paymentMapper
             ->expects($this->once())
             ->method('findByTransactionId')
             ->with($this->equalTo($transactionId))
             ->will($this->returnValue(new Payment()));

        $this->service->processResponse('');
    }

    public function test_processResponse_throws_if_payment_not_loaded()
    {
        $this->makeCryptServiceReturnResponse('VendorTxCode=TX123');

        $this->makeMapperReturnPayment(null);

        $this->setExpectedException(
            'SclZfCartSagepay\Exception\RuntimeException',
            'Payment with transaction ID "TX123" does not exist.'
        );

        $this->service->processResponse('');
    }

    public function test_processResponse_returns_payment()
    {
        $payment = new Payment();

        $this->makeCryptServiceReturnResponse();

        $this->makeMapperReturnPayment($payment);

        $this->assertSame($payment, $this->service->processResponse(''));
    }

    public function test_processResponse_throws_if_payment_has_already_completed()
    {
        $payment = new Payment();

        $payment->setTransactionId('TX123');

        $this->makeCryptServiceReturnResponse();

        $this->makeMapperReturnPayment($payment);

        $this->setPaymentComplete(true);

        $this->setExpectedException(
            'SclZfCartSagepay\Exception\RuntimeException',
            'Payment "TX123" has already been actioned.'
        );

        $this->service->processResponse('');
    }

    public function test_processResponse_success_payment()
    {
        $payment = new Payment();

        $this->makeCryptServiceReturnResponse('Status=OK');

        $this->makeMapperReturnPayment($payment);

        $this->paymentService
            ->expects($this->once())
            ->method('complete')
            ->with($this->identicalTo($payment));

        $this->service->processResponse('');
    }

    public function test_processResponse_success_does_not_call_fail()
    {
        $payment = new Payment();

        $this->makeCryptServiceReturnResponse('Status=OK');

        $this->makeMapperReturnPayment($payment);

        $this->paymentService
            ->expects($this->never())
            ->method('fail');

        $this->service->processResponse('');
    }

    public function test_processResponse_fail_payment()
    {
        $payment = new Payment();

        $this->makeCryptServiceReturnResponse('Status=NOTAUTHED');

        $this->makeMapperReturnPayment($payment);

        $this->paymentService
            ->expects($this->once())
            ->method('fail')
            ->with($this->identicalTo($payment));

        $this->service->processResponse('');
    }

    public function test_processResponse_fail_does_not_call_fail()
    {
        $payment = new Payment();

        $this->makeCryptServiceReturnResponse('Status=NOTAUTHED');

        $this->makeMapperReturnPayment($payment);

        $this->paymentService
            ->expects($this->never())
            ->method('complete');

        $this->service->processResponse('');
    }

    /*
     * Private methods
     */

    private function createOptions()
    {
        $options = new ConnectionOptions();

        $options->setPassword(self::TEST_PASSWORD);

        return $options;
    }

    private function createResponse($params = '')
    {
        $cryptService = new CryptService();

        return $cryptService->processResponseData($params);
    }

    private function makeCryptServiceReturnResponse($params = '')
    {
        $this->cryptService
             ->expects($this->any())
             ->method('processResponseData')
             ->will($this->returnValue($this->createResponse($params)));
    }

    private function makeMapperReturnPayment($payment)
    {
        $this->paymentMapper
             ->expects($this->any())
             ->method('findByTransactionId')
             ->will($this->returnValue($payment));
    }

    private function setPaymentComplete($complete)
    {
        $this->paymentService
             ->expects($this->any())
             ->method('isComplete')
             ->will($this->returnValue($complete));
    }
}
