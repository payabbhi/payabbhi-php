<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Virtual_Account
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

Class Virtual_Account extends ApiResource
{
    /**
     * @param string $id The ID of the Virtual_Account to retrieve.
     *
     * @return Virtual_Account
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Virtual_Accounts
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
     * @return Virtual_Account
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Virtual_Account
     */
    public function close($id)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . '/' . $extn, "PATCH", null);
    }

    /**
     * @param string $id The ID of the Virtual_Account to retrieve details.
     *
     * @return Payment
     */
     public function details($id)
     {
       $id = Util::utf8($id);
       $extn = urlencode($id);
       return self::_request('/api/v1/payments/' . $extn . "/virtual_accounts", "GET", null);
     }

}
