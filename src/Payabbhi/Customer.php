<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Customer
 *
 * @property string $id
 * @property string $object
 * @property string $name
 * @property string $email
 * @property string $contact_no
 * @property mixed $billing_address
 * @property mixed $shipping_address
 * @property string $gstin
 * @property mixed $notes
 * @property mixed $subscriptions
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Customer extends ApiResource
{
    /**
     * @param string $id The ID of the customer to retrieve.
     *
     * @return Customer
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Customers
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Customer
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Customer
     */
    public function edit($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        self::_request(static::classUrl() . '/' . $extn , "PUT", $params);
        return self::retrieve($extn);
    }


}
