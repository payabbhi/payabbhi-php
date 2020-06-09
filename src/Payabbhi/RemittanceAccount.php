<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class RemittanceAccount
 *
 * @property string $id
 * @property string $object
 * @property string $balance_amount
 * @property string $currency
 * @property string $account_type
 * @property string $ifsc
 * @property string $order_id
 * @property string $beneficiary_name
 * @property boolean $low_balance_alert
 *
 * @package Payabbhi
 */

Class RemittanceAccount extends ApiResource
{
    /**
     * @param string $id The ID of the RemittanceAccount to retrieve.
     *
     * @return RemittanceAccount
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

}
