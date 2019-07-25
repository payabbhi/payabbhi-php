<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Transfer
 *
 * @property string $id
 * @property string $object
 * @property string $description
 * @property string $source_id
 * @property string $beneficiary_id
 * @property int $amount
 * @property string $currency
 * @property int $fees
 * @property int $gst
 * @property int $amount_reversed
 * @property string $settlement_id
 * @property bool $settled
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Transfer extends ApiResource
{
    /**
     * @param string $id The ID of the transfer to retrieve.
     *
     * @return Transfer
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    /**
     * @param array|null $params
     *
     * @return Collection of Transfers
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Collection of Transfers
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

}
