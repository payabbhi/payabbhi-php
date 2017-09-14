<?php
namespace Payabbhi;

/**
 * Class Order
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $merchant_order_id
 * @property string $status
 * @property int payment_attempts
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Order extends ApiResource
{

    /**
     * @param string $id The ID of the order to retrieve.
     *
     * @return Order
     */
    public function retrieve($id)
    {
        return $this->_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Orders
     */
    public function all($params = null)
    {
        return $this->_all($params);

    }

    /**
     * @return Collection of Payments
     */
    public function payments()
    {
        return $this->_request(static::instanceUrl($this->getObjectIdentifier()) . "/payments", "GET",null);
    }

    /**
     * @param array|null $params.
     *
     * @return Order
     */
    public function create($params)
    {
        return $this->_request(static::classUrl(), "POST", $params);
    }
}
