<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class VirtualAccount
 *
 * @property string $id
 * @property string $object
 * @property string $status
 * @property int $paid_amount
 * @property string $customer_id
 * @property string $email
 * @property string $contact_no
 * @property string $order_id
 * @property string $invoice_id
 * @property string $description
 * @property mixed $collection_methods
 * @property string $notification_method
 * @property string $customer_notification_by
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class VirtualAccount extends ApiResource
{
    /**
     * @param string $id The ID of the VirtualAccount to retrieve.
     *
     * @return VirtualAccount
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of VirtualAccounts
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Payments
     */
    public function payments($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl(). '/' . $id . '/payments' , "GET", $params);
    }

    /**
     * @param array|null $params.
     *
     * @return VirtualAccount
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return VirtualAccount
     */
    public function close($id)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . '/' . $extn, "PATCH", null);
    }
}
