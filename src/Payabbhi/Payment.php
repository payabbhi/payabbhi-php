<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Payment
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $status
 * @property string $order_id
 * @property string $invoice_id
 * @property bool $international
 * @property string $method
 * @property string $description
 * @property string $card
 * @property string $bank
 * @property string $wallet
 * @property mixed $emi
 * @property string $vpa
 * @property string $customer_id
 * @property string $email
 * @property string $contact
 * @property mixed  $notes
 * @property int    $fee
 * @property int    $service_tax
 * @property int    $payout_amount
 * @property string $payout_type
 * @property bool $settled
 * @property string $settlement_id
 * @property int    $refunded_amount
 * @property string $refund_status
 * @property mixed $refunds
 * @property string $error_code
 * @property string $error_description
 * @property int $created_at
 * @property string $virtual_account_id
 * @property string $payment_page_id
 *
 * @package Payabbhi
 */

Class Payment extends ApiResource
{

    /**
     * @param string $id The ID of the payment to retrieve.
     *
     * @return Payment
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Payments
     */
    public function all($params = null)
    {
        return self::_all($params);

    }

    /**
     * @param string $id The refunds of the payment ID to retrieve.
     *
     * @return collection of Refunds
     */
    public function refunds($params = null)
    {
        return self::_request(static::instanceUrl($this->getObjectIdentifier()) . "/refunds", "GET", $params);
    }

    /**
     * @param string $id The transfers of the payment ID to retrieve.
     *
     * @return collection of Transfers
     */
    public function transfers($params = null)
    {
        return self::_request(static::instanceUrl($this->getObjectIdentifier()) . "/transfers", "GET", $params);
    }

    /**
     * @param string $id The ID of the payment to capture.
     *
     * @return Payment
     */
    public function capture($params = null)
    {
        return self::_request(static::instanceUrl($this->getObjectIdentifier()) . "/capture", "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Refund
     */
    public function refund($params = null)
    {
        $refund = new Refund;
        $refund->create($this->getObjectIdentifier(), $params);
        self::retrieve($this->getObjectIdentifier());
        return $refund;
    }

    /**
     * @param array|null $params
     *
     * @return collection of Transfers
     */
    public function transfer($params = null)
    {
        return self::_request(static::instanceUrl($this->getObjectIdentifier()) . "/transfers", "POST", $params);
    }

    /**
     * @param string $id The ID of the payment to retrieve.
     * @param array|null $params Filter Params
     * 
     * @return Payment with virtual account details
     */
     public function virtual_account($id, $params = null)
     {
        $id = Util::utf8($id);
        return self::_request(static::classUrl() . '/' . $id . '/virtual_account' , "GET", $params);
     }
}
