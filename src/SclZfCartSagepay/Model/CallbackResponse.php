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

    /**
     * Stop instance from being created explicitly.
     */
    private function __construct()
    {
    }

    /**
     * Create an instance from a URL encoded string
     *
     * @param  array $values
     *
     * @return CallbackResponse
     */
    public static function createFromArray(array $values)
    {
        $response = new self();

        $response->vendorTxCode   = self::getValueFromArray($values, 'VendorTxCode');
        $response->vpsTxId        = self::getValueFromArray($values, 'VPSTxId');
        $response->status         = self::getValueFromArray($values, 'Status');
        $response->statusDetail   = self::getValueFromArray($values, 'StatusDetail');
        $response->txAuthNo       = self::getValueFromArray($values, 'TxAuthNo');
        $response->avsCv2         = self::getValueFromArray($values, 'AVSCV2');
        $response->addressResult  = self::getValueFromArray($values, 'AddressResult');
        $response->postCodeResult = self::getValueFromArray($values, 'PostCodeResult');
        $response->cv2Result      = self::getValueFromArray($values, 'CV2Result');
        $response->giftAid        = self::getValueFromArray($values, 'GiftAid');
        $response->secureStatus3D = self::getValueFromArray($values, '3DSecureStatus');
        $response->cavv           = self::getValueFromArray($values, 'CAVV');
        $response->cardType       = self::getValueFromArray($values, 'CardType');
        $response->last4Digits    = self::getValueFromArray($values, 'Last4Digits');
        $response->declineCode    = self::getValueFromArray($values, 'DeclineCode');
        $response->amount         = self::getValueFromArray($values, 'Amount');
        $response->bankAuthCode   = self::getValueFromArray($values, 'BankAuthCode');

        return $response;
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

    /**
     * Returns a value from the array or null if the value doesn't exist,
     *
     * @param  string[] $data
     * @param  string   $key
     *
     * @return string|null
     */
    private static function getValueFromArray(array $data, $key)
    {
        return isset($data[$key]) ? $data[$key] : null;
    }

}
