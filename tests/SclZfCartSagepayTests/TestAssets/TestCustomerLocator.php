<?php

namespace SclZfCartSagepayTests\TestAssets;

use SclZfCart\Customer\CustomerLocatorInterface;
use SclZfCart\Customer\CustomerInterface;

class TestCustomerLocator implements CustomerLocatorInterface
{
    private static $customer;

    public static function setCustomer(CustomerInterface $customer)
    {
        self::$customer = $customer;
    }

    public function getActiveCustomer()
    {
        return self::$customer;
    }
}
