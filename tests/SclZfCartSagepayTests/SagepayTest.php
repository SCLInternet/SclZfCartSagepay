<?php
namespace SclZfCartSagepayTests;

use SclZfCartSagepay\Sagepay;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-15 at 17:30:38.
 */
class SagepayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sagepay
     */
    protected $object;

    protected $options;

    protected $connectionOptions;

    protected $blockCipher;

    protected $cryptData;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->options = $this->getMockBuilder('SclZfCartSagepay\Options\SagepayOptions')
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionOptions = $this->getMockBuilder('SclZfCartSagepay\Options\ConnectionOptions')
            ->disableOriginalConstructor()
            ->getMock();

        $this->options->expects($this->any())
            ->method('getConnectionOptions')
            ->will($this->returnValue($this->connectionOptions));

        $this->blockCipher = $this->getMockBuilder('Zend\Crypt\BlockCipher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cryptData = $this->getMock('SclZfCartSagepay\Data\CryptData');

        $this->object = new Sagepay($this->options, $this->blockCipher, $this->cryptData);
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::name
     * @todo   Implement testName().
     */
    public function testName()
    {
        $this->options->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Sagepay Name'));

        $this->assertEquals('Sagepay Name', $this->object->name());
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::__construct
     * @covers SclZfCartSagepay\Sagepay::updateCompleteForm
     * @covers SclZfCartSagepay\Sagepay::getCrypt
     * @covers SclZfCartSagepay\Sagepay::addHiddenField
     *
     * @return void
     */
    public function testUpdateCompleteForm()
    {
        $url = 'http://action.url';

        $this->connectionOptions
             ->expects($this->any())
             ->method('getUrl')
             ->will($this->returnValue($url));

        $form    = $this->getMock('Zend\Form\Form');
        $order   = $this->getMock('SclZfCart\Entity\OrderInterface');
        $payment = $this->getMock('SclZfCartPayment\Entity\PaymentInterface');

        $form->expects($this->once())
             ->method('setAttribute')
             ->with($this->equalTo('action'), $this->equalTo($url));

        // @todo Check the actual values being added
        $this->cryptData
             ->expects($this->any())
             ->method('add')
             ->will($this->returnValue($this->cryptData));

        // @todo Check form elements are being added

        $this->object->updateCompleteForm($form, $order, $payment);
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::complete
     * @todo   Implement testComplete().
     */
    public function testComplete()
    {
        $this->object->complete(array());
    }
}
