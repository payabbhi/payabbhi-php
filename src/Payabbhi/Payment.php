<?php
namespace Payabbhi;

/**
 * Class Payment
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $status
 * @property string $order_id
 * @property bool $international
 * @property string $method
 * @property string $description
 * @property string $card
 * @property string $bank
 * @property string $wallet
 * @property string $email
 * @property string $contact
 * @property mixed  $notes
 * @property int    $fee
 * @property int    $service_tax
 * @property int    $payout_amount
 * @property string $payout_type
 * @property int    $refunded_amount
 * @property string $refund_status
 * @property mixed $refunds
 * @property string $error_code
 * @property string $error_description
 * @property int $created_at
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
}
