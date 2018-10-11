<?php
namespace Payabbhi;

/**
 * Class Plan
 *
 * @property string $id
 * @property string $object
 * @property string $product_id
 * @property string $name
 * @property int $amount
 * @property string $currency
 * @property int $frequency
 * @property string $interval
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Plan extends ApiResource
{
    /**
     * @param string $id The ID of the plan to retrieve.
     *
     * @return Plan
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    /**
     * @param array|null $params
     *
     * @return Collection of Plan
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Plan
     */
    public function create($params)
    {
        return $this->_request(static::classUrl(), "POST", $params);
    }

}
