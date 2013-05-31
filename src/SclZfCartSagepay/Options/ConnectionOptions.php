<?php

namespace SclZfCartSagepay\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Set of options required to connect to the server.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ConnectionOptions extends AbstractOptions
{
    /**
     * The URL of the server
     *
     * @var string
     */
    protected $url;

    /**
     * The encryption password.
     *
     * @var string
     */
    protected $password;

    /**
     * Gets the value of url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url
     *
     * @param  string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
        return $this;
    }

    /**
     * Gets the value of password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value of password
     *
     * @param  string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }
}
