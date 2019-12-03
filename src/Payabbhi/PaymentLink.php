<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class PaymentLink
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $customer_id
 * @property string $name
 * @property string $email
 * @property string $contact_no
 * @property string $currency
 * @property string $description
 * @property int $due_date
 * @property mixed $notes
 * @property string $notify_by
 * @property string $customer_notification_by
 * @property string $payment_attempt
 * @property string $receipt_no
 * @property string $status
 * @property string $url
 * @property int $created_at
 *
 * @package Payabbhi
 */


Class PaymentLink extends ApiResource
{

    /**
     * @param string $id The ID of the PaymentLink to retrieve.
     *
     * @return PaymentLink
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of PaymentLinks
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of PaymentLinks
     */
    public function payments($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl(). '/' . $id . '/payments' , "GET", $params);
    }

    /**
     * @param array|null $params
     *
     * @return PaymentLink
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return PaymentLink
     */
    public function cancel($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request(static::classUrl() . '/' . $extn . '/cancel' , "POST", $params);
    }

}
