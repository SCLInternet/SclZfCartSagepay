<?php
namespace SclZfCartSagepay;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-15 at 17:30:38.
 */
class SagepayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sagepay
     */
    protected $object;

    protected $dataProvider;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->dataProvider = $this->getMockBuilder('SclZfCartSagepay\Data\DataProvider')->disableOriginalConstructor()->getMock();
        $this->object = new Sagepay($this->dataProvider);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::name
     * @todo   Implement testName().
     */
    public function testName()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::updateCompleteForm
     * @todo   Implement testUpdateCompleteForm().
     */
    public function testUpdateCompleteForm()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Sagepay::complete
     * @todo   Implement testComplete().
     */
    public function testComplete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
