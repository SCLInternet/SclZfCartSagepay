<?php

namespace SclZfCartSagepay\Service;

use SclZfCart\Customer\CustomerInterface;
use SclZfCart\Entity\Order;
use SclZfCartSagepay\Model\CallbackResponse;

class CryptService
{
    /**
     * Build a crypt data block for Sagepay.
     *
     * @param  string $transactionId
     * @param  string $currency
     * @param  string $successUrl
     * @param  string $failureUrl
     *
     * @return array
     */
    public function createCryptData(
        Order $order,
        CustomerInterface $customer,
        $transactionId,
        $currency,
        $successUrl,
        $failureUrl
    ) {
        return $this->getVarString(array_merge(
            [
                'VendorTxCode' => $transactionId,
                'Amount'       => $order->getTotal()->getValue(),
                'Currency'     => $currency,
                'Description'  => 'Online Order',
                'SuccessURL'   => $successUrl,
                'FailureURL'   => $failureUrl,
            ],
            $this->createContactData('Billing', $customer),
            $this->createContactData('Delivery', $customer)
        ));
    }

    /**
     * Takes a response string and proccesses it into a CallbackResponse object.
     *
     * @param  string $data
     *
     * @return CallbackResponse
     */
    public function processResponseData($data)
    {
        parse_str($data, $values);

        return CallbackResponse::createFromArray($values);
    }

    private function valueIfExists(array $data, $key)
    {
        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
     * Returns a HTTP GET style parameter string.
     *
     * @param  array $values
     *
     * @return string
     */
    public function getVarString(array $values)
    {
        return implode('&', array_map(
            function ($key, $value) {
                return $key . '=' . $value;
            },
            array_keys($values),
            $values
        ));
    }

    /**
     * Creates a dataset for a set of contact details.
     *
     * @param  string $prefix
     *
     * @return array
     */
    public function createContactData($prefix, CustomerInterface $customer)
    {
        $contact = $customer->getContact();
        $address = $contact->getAddress();

        return [
            $prefix . 'Surname'    => $contact->getName()->getLastName(),
            $prefix . 'Firstnames' => $contact->getName()->getFirstName(),
            $prefix . 'Address1'   => $address->getLine1(),
            $prefix . 'Address2'   => $address->getLine2(),
            $prefix . 'City'       => $address->getCity(),
            $prefix . 'PostCode'   => $address->getPostCode()->get(),
            $prefix . 'Country'    => strtoupper($address->getCountry()->getCode()),
        ];
    }
}
