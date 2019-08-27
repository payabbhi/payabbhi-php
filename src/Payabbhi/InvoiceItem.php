<?php
namespace Payabbhi;

use Payabbhi\Util\Util;

/**
 * Class InvoiceItem
 *
 * @property string $id
 * @property string $object
 * @property string $name
 * @property int $amount
 * @property string $currency
 * @property string $description
 * @property int $unit
 * @property int $quantity
 * @property int $discount
 * @property string $hsn_code
 * @property string $sac_code
 * @property int $tax_rate
 * @property int $cess
 * @property bool $tax_inclusive
 * @property int $tax_amount
 * @property int $net_amount
 * @property string $customer_id
 * @property int $deleted_at
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class InvoiceItem extends ApiResource
{
    /**
     * @param string $id The ID of the invoiceitem to retrieve.
     *
     * @return InvoiceItem
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of InvoiceItems
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return InvoiceItem
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return InvoiceItem
     */
    public function delete($id)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . '/' . $extn, "DELETE", null);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Invoices
     */
    public function invoices($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . '/' . $id . '/invoices', "GET", $params);
    }
}
