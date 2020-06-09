<?php
namespace Payabbhi;

/**
 * Class Payout
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $merchant_reference_id
 * @property string $beneficiary_account_no
 * @property string $beneficiary_ifsc
 * @property string $beneficiary_name
 * @property string $method
 * @property string $purpose
 * @property string $narration
 * @property string $instrument
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Payout extends ApiResource
{
    /**
     * @param string $id The ID of the payout to retrieve.
     *
     * @return Payout
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    /**
     * @param array|null $params
     *
     * @return Collection of Payout
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Payout
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

}
