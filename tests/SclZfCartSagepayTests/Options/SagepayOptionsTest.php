<?php

namespace SclZfCartSagepayTests\Options;

use SclZfCartSagepay\Options\SagepayOptions;
use SclZfCartSagepay\Options\ConnectionOptions;

/**
 * Unit tests for {@see ConnectionOptions}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class SagepayOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested
     *
     * @var SagepayOptions
     */
    protected $options;

    /**
     * Prepare the object to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->options = new SagepayOptions;
    }

    protected function getSetCheck($property, $value)
    {
        $getter = "get$property";
        $setter = "set$property";

        $this->options->$setter($value);

        $this->assertEquals(
            $value,
            $this->options->$getter(),
            "$property is incorrect"
        );
    }

    /**
     * Test the getters and setters
     *
     * @covers SclZfCartSagepay\Options\SagepayOptions::getLive
     * @covers SclZfCartSagepay\Options\SagepayOptions::setLive
     * @covers SclZfCartSagepay\Options\SagepayOptions::getName
     * @covers SclZfCartSagepay\Options\SagepayOptions::setName
     * @covers SclZfCartSagepay\Options\SagepayOptions::getAccount
     * @covers SclZfCartSagepay\Options\SagepayOptions::setAccount
     * @covers SclZfCartSagepay\Options\SagepayOptions::getVersion
     * @covers SclZfCartSagepay\Options\SagepayOptions::setVersion
     * @covers SclZfCartSagepay\Options\SagepayOptions::getCurrency
     * @covers SclZfCartSagepay\Options\SagepayOptions::setCurrency
     * @covers SclZfCartSagepay\Options\SagepayOptions::getTxDescription
     * @covers SclZfCartSagepay\Options\SagepayOptions::setTxDescription
     * @covers SclZfCartSagepay\Options\SagepayOptions::getLiveConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::setLiveConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::getTestConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::setTestConnection
     *
     * @return void
     */
    public function testGetSet()
    {
        $this->getSetCheck('live', true);
        $this->getSetCheck('name', 'the_name');
        $this->getSetCheck('account', 'the_account');
        $this->getSetCheck('version', '10.0');
        $this->getSetCheck('currency', 'GBP');
        $this->getSetCheck('txDescription', 'the_description');
        $this->getSetCheck('liveConnection', $this->getMock('SclZfCartSagepay\Options\ConnectionOptions'));
        $this->getSetCheck('testConnection', $this->getMock('SclZfCartSagepay\Options\ConnectionOptions'));
    }

    /**
     * Test the set*Connection method when passed an array.
     *
     * @covers SclZfCartSagepay\Options\SagepayOptions::getLiveConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::setLiveConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::getTestConnection
     * @covers SclZfCartSagepay\Options\SagepayOptions::setTestConnection
     *
     * @return void
     */
    public function testConnectionSettersWithArray()
    {
        $live = array(
            'url'      => 'live_url',
            'password' => 'live_pw',
        );
        $test = array(
            'url'      => 'test_url',
            'password' => 'test_pw',
        );

        $this->options->setLiveConnection($live);
        $this->options->setTestConnection($test);

        $liveConn = $this->options->getLiveConnection();
        $testConn = $this->options->getTestConnection();

        $this->assertEquals($live['url'], $liveConn->getUrl(), 'Live URL is incorrect.');
        $this->assertEquals($test['url'], $testConn->getUrl(), 'Test URL is incorrect.');

        $this->assertEquals($live['password'], $liveConn->getPassword(), 'Live password is incorrect.');
        $this->assertEquals($test['password'], $testConn->getPassword(), 'Test password is incorrect.');
    }

    /**
     * When setLiveConnection is called with an object which is not an instance
     * of ConnectionOptions then InvalidArgumentException should be thrown.
     *
     * @covers SclZfCartSagepay\Options\SagepayOptions::setLiveConnection
     * @expectedException SclZfCartSagepay\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetLiveConnectionWithBadObject()
    {
        $this->options->setLiveConnection(new \stdClass);
    }

    /**
     * When setTestConnection is called with an object which is not an instance
     * of ConnectionOptions then InvalidArgumentException should be thrown.
     *
     * @covers SclZfCartSagepay\Options\SagepayOptions::setTestConnection
     * @expectedException SclZfCartSagepay\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetTestConnectionWithBadObject()
    {
        $this->options->setTestConnection(new \stdClass);
    }

    /**
     * testGetConnection
     *
     * @covers SclZfCartSagepay\Options\SagepayOptions::getConnectionOptions
     *
     * @return void
     */
    public function testGetConnection()
    {
        $liveConnection = new ConnectionOptions();
        $liveConnection->setUrl('liveurl');
        $testConnection = new ConnectionOptions();
        $liveConnection->setUrl('testurl');

        $this->options->setLiveConnection($liveConnection);
        $this->options->setTestConnection($testConnection);

        $this->options->setLive(false);
        $this->assertEquals(
            $testConnection,
            $this->options->getConnectionOptions(),
            'Test options are wrong'
        );

        $this->options->setLive(true);
        $this->assertEquals(
            $liveConnection,
            $this->options->getConnectionOptions(),
            'Live options are wrong'
        );
    }
}
