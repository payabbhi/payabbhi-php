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
 * @property string $recipient_id
 * @property int $amount
 * @property string $currency
 * @property int $fees
 * @property int $gst
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
     * @return Collection of Transfer
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Transfer
     */
    public function create($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request('/api/v1/payments/' . $extn . "/transfers", "POST", $params);
    }

}
