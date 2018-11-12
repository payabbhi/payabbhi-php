<?php
namespace Payabbhi;
use Payabbhi\Util\Util;

/**
 * Class Subscription
 *
 * @property string $id
 * @property string $object
 * @property string $plan_id
 * @property string $customer_id
 * @property string $billing_method
 * @property int $quantity
 * @property string $customer_notification_by
 * @property int $billing_cycle_count
 * @property int $paid_count
 * @property bool $cancel_at_period_end
 * @property int $due_by_days
 * @property int $trial_end_at
 * @property string $status
 * @property int $current_start_at
 * @property int $current_end_at
 * @property int $ended_at
 * @property int $cancelled_at
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class Subscription extends ApiResource
{
    /**
     * @param string $id The ID of the subscription to retrieve.
     *
     * @return Subscription
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }

    /**
     * @param array|null $params
     *
     * @return Collection of Subscriptions
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return Subscription
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

    /**
     * @param array|null $params
     *
     * @return Subscription
     */
    public function cancel($id, $params = null)
    {
        $id = Util::utf8($id);
        $extn = urlencode($id);
        return self::_request('/api/v1/subscriptions/' . $extn . '/cancel' , "POST", $params);
    }


}
