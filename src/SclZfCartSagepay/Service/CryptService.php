<?php

namespace SclZfCartSagepay\Service;

use SclZfCart\Customer\CustomerInterface;
use SclZfCart\Entity\OrderInterface;
use SclZfCartSagepay\Model\CallbackResponse;

class CryptService
{
    /**
     * Build a crypt data block for Sagepay.
     *
     * @param  OrderInterface    $order
     * @param  CustomerInterface $customer
     * @param  string            $transactionId
     * @param  string            $currency
     * @param  string            $successUrl
     * @param  string            $failureUrl
     *
     * @return array
     */
    public function createCryptData(
        OrderInterface $order,
        CustomerInterface $customer,
        $transactionId,
        $currency,
        $successUrl,
        $failureUrl
    ) {
        return $this->getVarString(array_merge(
            [
                'VendorTxCode' => $transactionId,
                'Amount'       => $order->getTotal(),
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
        /*
        VendorTxCode=TEST-SCL-TX-21
        VPSTxId={FA960C35-1331-FDD4-1611-447B4C76038C}
        Status=OK
        StatusDetail=0000 : The Authorisation was Successful.
        TxAuthNo=5820406
        AVSCV2=ALL MATCH
        AddressResult=MATCHED
        PostCodeResult=MATCHED
        CV2Result=MATCHED
        GiftAid=0
        3DSecureStatus=OK
        CAVV=AAABARR5kwAAAAAAAAAAAAAAAAA=
        CardType=VISA
        Last4Digits=0006
        DeclineCode=00
        Amount=11.99
        BankAuthCode=999777
        */
        parse_str($data, $values);

        return new CallbackResponse(
            $this->valueIfExists($values, 'VendorTxCode'),
            $this->valueIfExists($values, 'VPSTxId'),
            $this->valueIfExists($values, 'Status'),
            $this->valueIfExists($values, 'StatusDetail'),
            $this->valueIfExists($values, 'TxAuthNo'),
            $this->valueIfExists($values, 'AVSCV2'),
            $this->valueIfExists($values, 'AddressResult'),
            $this->valueIfExists($values, 'PostCodeResult'),
            $this->valueIfExists($values, 'CV2Result'),
            $this->valueIfExists($values, 'GiftAid'),
            $this->valueIfExists($values, '3DSecureStatus'),
            $this->valueIfExists($values, 'CAVV'),
            $this->valueIfExists($values, 'CardType'),
            $this->valueIfExists($values, 'Last4Digits'),
            $this->valueIfExists($values, 'DeclineCode'),
            $this->valueIfExists($values, 'Amount'),
            $this->valueIfExists($values, 'BankAuthCode')
        );
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
     * @param  string            $prefix
     * @param  CustomerInterface $customer
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
