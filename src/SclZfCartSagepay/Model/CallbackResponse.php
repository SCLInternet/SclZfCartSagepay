<?php

namespace SclZfCartSagepay\Model;

class CallbackResponse
{
    /**
     * Transaction completed successfully with authorisation.
     */
    const STATUS_OK = 'OK';

    /**
     * PENDING
     */
    const STATUS_PENDING = 'PENDING';

    /**
     * The Sage Pay system could not authorise the
     * transaction because the details provided by the Customer were
     * incorrect, or insufficient funds were available. However the
     * Transaction has completed through the Sage Pay System.
     */
    const STATUS_NOTAUTHED = 'NOTAUTHED';

    /**
     * Input message was missing fields or badly formatted
     * - normally will only occur during development and vendor
     * integration.
     */
    const STATUS_MALFORMED = 'MALFORMED';

    /**
     * Transaction was not registered because although the
     * POST format was valid, some information supplied was invalid. e.g.
     * incorrect vendor name or currency.
     */
    const STATUS_INVALID = 'INVALID';

    /**
     * The Transaction could not be completed because the user
     * clicked the CANCEL button on the payment pages, or went inactive
     * for 15 minutes or longer.
     */
    const STATUS_ABORT = 'ABORT';

    /**
     * The Sage Pay System rejected the transaction because
     * of the fraud screening rules you have set on your account.
     * Note : The bank may have authorised the transaction but your own
     * rule bases for AVS/CV2 or 3D-Secure caused the transaction to be
     * rejected.
     */
    const STATUS_REJECTED = 'REJECTED';

    /**
     * The 3D-Secure checks were performed
     * successfully and the card details secured at Sage Pay. Only returned
     * if TxType is AUTHENTICATE.
     */
    const STATUS_AUTHENTICATED = 'AUTHENTICATED';

    /**
     * 3D-Secure checks failed or were not performed, but
     * the card details are still secured at Sage Pay. Only returned if TxType
     * is AUTHENTICATE.
     */
    const STATUS_REGISTERED = 'REGISTERED';

    /**
     * A problem occurred at Sage Pay which prevented
     * transaction completion.
     * Please notify Sage Pay if a Status report of ERROR is seen, together
     * with your VendorTxCode and the StatusDetail text.
     */
    const STATUS_ERROR = 'ERROR';

    /**
     * List of successful statuses.
     */
    private static $successStates = [
        self::STATUS_OK,
        self::STATUS_AUTHENTICATED,
    ];

    private $vendorTxCode;

    private $vpsTxId;

    private $status;

    private $statusDetail;

    private $txAuthNo;

    private $avsCv2;

    private $addressResult;

    private $postCodeResult;

    private $cv2Result;

    private $giftAid;

    private $secureStatus3D;

    private $cavv;

    private $cardType;

    private $last4Digits;

    private $declineCode;

    private $amount;

    private $bankAuthCode;

    public function __construct(
        $vendorTxCode,
        $vpsTxId,
        $status,
        $statusDetail,
        $txAuthNo,
        $avsCv2,
        $addressResult,
        $postCodeResult,
        $cv2Result,
        $giftAid,
        $secureStatus3D,
        $cavv,
        $cardType,
        $last4Digits,
        $declineCode,
        $amount,
        $bankAuthCode
    ) {
        $this->vendorTxCode   = $vendorTxCode;
        $this->vpsTxId        = $vpsTxId;
        $this->status         = $status;
        $this->statusDetail   = $statusDetail;
        $this->txAuthNo       = $txAuthNo;
        $this->avsCv2         = $avsCv2;
        $this->addressResult  = $addressResult;
        $this->postCodeResult = $postCodeResult;
        $this->cv2Result      = $cv2Result;
        $this->giftAid        = $giftAid;
        $this->secureStatus3D = $secureStatus3D;
        $this->cavv           = $cavv;
        $this->cardType       = $cardType;
        $this->last4Digits    = $last4Digits;
        $this->declineCode    = $declineCode;
        $this->amount         = $amount;
        $this->bankAuthCode   = $bankAuthCode;
    }

    /**
     * Make properties read only.
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Was the transaction a success?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return in_array($this->status, self::$successStates);
    }
}
