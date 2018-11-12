<?php
namespace Payabbhi;

/**
 * Class Product
 *
 * @property string $id
 * @property string $object
 * @property string $name
 * @property string $type
 * @property string $unit_label
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Product extends ApiResource
{
    /**
     * @param string $id The ID of the product to retrieve.
     *
     * @return Product
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    /**
     * @param array|null $params
     *
     * @return Collection of Products
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Product
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

}
