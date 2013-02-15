<?php

namespace SclZfCartSagepay\Data;

/**
 * Builds up the crypt string to be encrypted.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CryptData
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @todo Look into string encoding.
     * @param string $field
     * @param string $value
     * @return void
     */
    public function add($field, $value)
    {
        $this->data[] = $field . '=' . $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode('&', $this->data);
    }
}