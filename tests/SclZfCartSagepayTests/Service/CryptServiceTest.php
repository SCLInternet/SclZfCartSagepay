<?php

namespace SclZfCartSagepayTests\Service;

use SclZfCartSagepay\Service\CryptService;
use SclContact\Contact;
use SclContact\Address;
use SclContact\PersonName;
use SclContact\Postcode;
use SclContact\Country;
use SclZfCart\Entity\Order;
use SclZfCart\Entity\OrderItem;

/**
 * Unit tests for {@see CryptService}.
 *
 * @covers SclZfCartSagepay\Service\CryptService
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CryptServiceTest extends \PHPUnit_Framework_TestCase
{
    const FIRST_NAME = 'Jack';
    const LAST_NAME  = 'Daniels';
    const LINE1      = 'The House';
    const LINE2      = 'The Place';
    const CITY       = 'Big City';
    const POSTCODE   = 'AB12 3CD';
    const COUNTRY    = 'GB';

    protected $service;

    protected function setUp()
    {
        $this->service = new CryptService();
    }

    public function test_getVarString_returns_var_style_string()
    {
        $this->assertEquals(
            'p1=v1&p2=v2',
            $this->service->getVarString(['p1' => 'v1', 'p2' => 'v2'])
        );
    }

    public function test_createContactData_adds_contact_parameters()
    {
        $data = $this->service->createContactData('Prefix', $this->createCustomer());

        $this->assertArrayContainsContact('Prefix', $data);
    }

    /*
     * createCryptData()
     */

    public function test_createCryptData()
    {
        // Given

        $transactionId = '123';
        $currency      = 'GBP';
        $successUrl    = 'http://success';
        $failureUrl    = 'http://fail';

        $order = new Order();
        $item = new OrderItem();
        $order->addItem($item);

        $item->setPrice(10);
        $item->setTax(2);

        // When

        $cryptData = $this->service->createCryptData(
            $order,
            $this->createCustomer(),
            $transactionId,
            $currency,
            $successUrl,
            $failureUrl
        );

        // Then

        $this->assertVarStringValue($transactionId, 'VendorTxCode', $cryptData);
        $this->assertVarStringValue(12, 'Amount', $cryptData);
        $this->assertVarStringValue($currency, 'Currency', $cryptData);
        $this->assertVarStringValue('Online Order', 'Description', $cryptData);
        $this->assertVarStringValue($successUrl, 'SuccessURL', $cryptData);
        $this->assertVarStringValue($failureUrl, 'FailureURL', $cryptData);

        $this->assertVarStringContainsContact('Billing', $cryptData);
        $this->assertVarStringContainsContact('Delivery', $cryptData);
    }

    /*
     * processResponseData()
     */

    public function test_processResponseData_return_CallbackResponse()
    {
        $this->assertInstanceOf(
            'SclZfCartSagepay\Model\CallbackResponse',
            $this->service->processResponseData('')
        );
    }

    public function test_processResponseData_sets_venderTxCode()
    {
        $data = 'VendorTxCode=TX123'
            . '&VPSTxId={123}'
            . '&Status=OK'
            . '&StatusDetail=0000 : The Authorisation was Successful.'
            . '&TxAuthNo=123456'
            . '&AVSCV2=ALL MATCH'
            . '&AddressResult=MATCHED'
            . '&PostCodeResult=MATCHED'
            . '&CV2Result=MATCHED'
            . '&GiftAid=0'
            . '&3DSecureStatus=OK'
            . '&CAVV=AAABBB'
            . '&CardType=VISA'
            . '&Last4Digits=0001'
            . '&DeclineCode=00'
            . '&Amount=9.99'
            . '&BankAuthCode=999'
            ;

        $response = $this->service->processResponseData($data);

        $this->assertEquals('TX123', $response->vendorTxCode);
        $this->assertEquals('{123}', $response->vpsTxId);
        $this->assertEquals('OK', $response->status);
        $this->assertEquals('0000 : The Authorisation was Successful.', $response->statusDetail);
        $this->assertEquals('123456', $response->txAuthNo);
        $this->assertEquals('ALL MATCH', $response->avsCv2);
        $this->assertEquals('MATCHED', $response->addressResult);
        $this->assertEquals('MATCHED', $response->postCodeResult);
        $this->assertEquals('MATCHED', $response->cv2Result);
        $this->assertEquals('0', $response->giftAid);
        $this->assertEquals('OK', $response->secureStatus3D);
        $this->assertEquals('AAABBB', $response->cavv);
        $this->assertEquals('VISA', $response->cardType);
        $this->assertEquals('0001', $response->last4Digits);
        $this->assertEquals('00', $response->declineCode);
        $this->assertEquals('9.99', $response->amount);
        $this->assertEquals('999', $response->bankAuthCode);
    }

    /*
     * Private methods
     */

    private function createCustomer()
    {
        $contact = new Contact();
        $address = new Address();

        $contact->setName(new PersonName(self::FIRST_NAME, self::LAST_NAME));

        $address->setLine1(self::LINE1);
        $address->setLine2(self::LINE2);
        $address->setCity(self::CITY);
        $address->setPostCode(new Postcode(self::POSTCODE));
        $address->setCountry(new Country(self::COUNTRY));

        $contact->setAddress($address);

        $customer = $this->getMock('SclZfCart\Customer\CustomerInterface');

        $customer->expects($this->any())
                 ->method('getContact')
                 ->will($this->returnValue($contact));

        return $customer;
    }

    private function assertArrayValue($expected, $key, array $array)
    {
        $this->assertArrayHasKey($key, $array, "Array does not contain key '$key'");

        $this->assertEquals(
            $expected,
            $array[$key],
            "Array value is incorrect for key '$key'"
        );
    }

    private function assertVarStringValue($expected, $key, $string)
    {
        parse_str($string, $values);

        $this->assertArrayValue($expected, $key, $values);
    }

    private function assertArrayContainsContact($prefix, array $array)
    {
        $this->assertArrayValue(self::LAST_NAME, $prefix . 'Surname', $array);
        $this->assertArrayValue(self::FIRST_NAME, $prefix . 'Firstnames', $array);
        $this->assertArrayValue(self::LINE1, $prefix . 'Address1', $array);
        $this->assertArrayValue(self::LINE2, $prefix . 'Address2', $array);
        $this->assertArrayValue(self::CITY, $prefix . 'City', $array);
        $this->assertArrayValue(self::POSTCODE, $prefix . 'PostCode', $array);
        $this->assertArrayValue(self::COUNTRY, $prefix . 'Country', $array);
    }

    private function assertVarStringContainsContact($prefix, $string)
    {
        parse_str($string, $values);

        $this->assertArrayContainsContact($prefix, $values);
    }
}
