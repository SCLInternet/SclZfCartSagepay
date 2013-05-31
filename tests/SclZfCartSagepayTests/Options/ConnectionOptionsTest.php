<?php

namespace SclZfCartSagepayTests\Options;

use SclZfCartSagepay\Options\ConnectionOptions;

/**
 * Unit tests for {@see ConnectionOptions}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConnectionOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested
     *
     * @var ConnectionOptions
     */
    protected $options;

    /**
     * Prepare the object to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->options = new ConnectionOptions;
    }

    /**
     * Test the getters and setters
     *
     * @covers SclZfCartSagepay\Options\ConnectionOptions::getUrl
     * @covers SclZfCartSagepay\Options\ConnectionOptions::setUrl
     * @covers SclZfCartSagepay\Options\ConnectionOptions::getPassword
     * @covers SclZfCartSagepay\Options\ConnectionOptions::setPassword
     *
     * @return void
     */
    public function testGetSet()
    {
        $url = 'http://some.url';
        $password = 'secret';

        $this->options->setUrl($url);
        $this->assertEquals(
            $url,
            $this->options->getUrl(),
            'URL is incorrect.'
        );

        $this->options->setPassword($password);
        $this->assertEquals(
            $password,
            $this->options->getPassword(),
            'password is incorrect.'
        );
    }
}
