<?php
namespace Payabbhi;

/**
 * Class Settlement
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $status
 * @property int $fees
 * @property int $gst
 * @property string $utr
 * @property int $settled_at
 *
 * @package Payabbhi
 */

Class Settlement extends ApiResource
{
    /**
     * @param string $id The ID of the settlement to retrieve.
     *
     * @return Settlement
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    
    /**
     * @param array|null $params
     *
     * @return Collection of Settlements
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

}
