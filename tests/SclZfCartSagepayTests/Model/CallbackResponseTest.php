<?php

namespace SclZfCartSagepayTests\Model;

use SclZfCartSagepay\Model\CallbackResponse;

/**
 * Unit tests for {@see CallbackResponse}.
 *
 * @covers SclZfCartSagepay\Model\CallbackResponse
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CallbackResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_createFromString()
    {
        $data = [
            'VendorTxCode'   => 'TX123',
            'VPSTxId'        => '{123}',
            'Status'         => 'OK',
            'StatusDetail'   => '0000 : The Authorisation was Successful.',
            'TxAuthNo'       => '123456',
            'AVSCV2'         => 'ALL MATCH',
            'AddressResult'  => 'MATCHED',
            'PostCodeResult' => 'MATCHED',
            'CV2Result'      => 'MATCHED',
            'GiftAid'        => '0',
            '3DSecureStatus' => 'OK',
            'CAVV'           => 'AAABBB',
            'CardType'       => 'VISA',
            'Last4Digits'    => '0001',
            'DeclineCode'    => '00',
            'Amount'         => '9.99',
            'BankAuthCode'   => '999',
        ];

        $response = CallbackResponse::createFromArray($data);

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

    public function test_isSuccess_returns_success_for_OK()
    {
        $response = $this->createResponseWithStatus(CallbackResponse::STATUS_OK);

        $this->assertTrue($response->isSuccess());
    }

    public function test_isSuccess_returns_success_for_AUTHENTICATED()
    {
        $response = $this->createResponseWithStatus(CallbackResponse::STATUS_AUTHENTICATED);

        $this->assertTrue($response->isSuccess());
    }

    public function test_isSuccess_returns_false_for_fail_status()
    {
        $failStates = [
            CallbackResponse::STATUS_PENDING,
            CallbackResponse::STATUS_NOTAUTHED,
            CallbackResponse::STATUS_MALFORMED,
            CallbackResponse::STATUS_INVALID,
            CallbackResponse::STATUS_ABORT,
            CallbackResponse::STATUS_REJECTED,
            CallbackResponse::STATUS_REGISTERED,
            CallbackResponse::STATUS_ERROR,
        ];

        foreach ($failStates as $status) {
            $response = $this->createResponseWithStatus($status);

            $this->assertFalse($response->isSuccess(), "$status is a fail status");
        }
    }

    /*
     * Private methods
     */

    private function createResponseWithStatus($status)
    {
        return CallbackResponse::createFromArray(['Status' => $status]);
    }
}
