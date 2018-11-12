<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Invoice
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $billing_method
 * @property string $customer_id
 * @property string $currency
 * @property string $description
 * @property int $due_date
 * @property mixed $line_items
 * @property string $notify_by
 * @property int $payment_attempt
 * @property mixed $payments
 * @property string $invoice_no
 * @property string $status
 * @property string $subscription_id
 * @property string $url
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Invoice extends ApiResource
{
    /**
     * @param string $id The ID of the invoice to retrieve.
     *
     * @return Invoice
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Invoices
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of InvoiceItems
     */
    public function line_items($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . $id . '/line_items' , "GET", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Payments
     */
    public function payments($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . $id . '/payments' , "GET", $params);
    }

    /**
     * @param array|null $params.
     *
     * @return Invoice
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Invoice
     */
    public function cancel($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . $extn . '/cancel' , "POST", $params);
    }


}
