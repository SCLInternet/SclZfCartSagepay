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


    private function createResponseWithStatus($status)
    {
        return new CallbackResponse(
            '',
            '',
            $status,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );
    }
}
