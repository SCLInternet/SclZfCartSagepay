<?php
namespace SclZfCartSagepay\Data;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-15 at 17:30:37.
 */
class DataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataProvider
     */
    protected $object;

    protected $blockCipher;

    protected $cryptData;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $config = array(
            'live'        => false,
            'vsp_account' => '',
            'version'     => '3.00',
            'connection'  => array(
                'live' => array(
                    'url' => 'https://live.sagepay.com/gateway/service/vspform-register.vsp',
                    'encryption_password' => '',
                ),
                'test' => array(
                    'url' => 'https://test.sagepay.com/gateway/service/vspform-register.vsp',
                    'encryption_password' => '',
                ),
            ),
        );

        $this->blockCipher = $this->getMockBuilder('Zend\Crypt\BlockCipher')->disableOriginalConstructor()->getMock();

        $this->cryptData = $this->getMock('SclZfCartSagepay\Data\CryptData');
    
        $this->object = new DataProvider($config, $this->blockCipher, $this->cryptData);;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers SclZfCartSagepay\Data\DataProvider::setCart
     * @todo   Implement testSetCart().
     */
    public function testSetCart()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Data\DataProvider::getVersion
     * @todo   Implement testGetVersion().
     */
    public function testGetVersion()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Data\DataProvider::getAccount
     * @todo   Implement testGetAccount().
     */
    public function testGetAccount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Data\DataProvider::getUrl
     * @todo   Implement testGetUrl().
     */
    public function testGetUrl()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers SclZfCartSagepay\Data\DataProvider::getCrypt
     * @todo   Implement testGetCrypt().
     */
    public function testGetCrypt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}