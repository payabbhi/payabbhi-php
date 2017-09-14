<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Refund
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $currency
 * @property string $payment_id
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Refund extends ApiResource
{
    /**
     * @param string $id The ID of the refund to retrieve.
     *
     * @return Refund
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    /**
     * @param array|null $params
     *
     * @return Collection of Refunds
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param string $id The ID of the payment to refund.
     * @param array|null $params
     *
     * @return Refund
     */
    public function create($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request('/api/v1/payments/' . $extn . "/refunds", "POST", $params);
    }

}
