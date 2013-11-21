<?php

namespace SclZfCartSagepayTests\Service;

use SclZfCartSagepay\Service\CallbackService;
use SclZfCartSagepay\Options\ConnectionOptions;

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

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->cipher = $this->getMock('SclZfCartSagepay\Encryption\Cipher');

        $this->cryptService = $this->getMock('SclZfCartSagepay\Service\CryptService');

        $this->service = new CallbackService(
            $this->cipher,
            $this->createOptions(),
            $this->cryptService
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
             ->with($this->equalTo($data));

        $this->service->processResponse($data);
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
}
