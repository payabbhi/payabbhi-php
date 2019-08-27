<?php
namespace Payabbhi;

/**
 * Class BeneficiaryAccount
 *
 * @property string $id
 * @property string $object
 * @property string $name
 * @property string $contact_no
 * @property string $email_id
 * @property string $business_name
 * @property string $business_entity_type
 * @property string $beneficiary_name
 * @property string $ifsc
 * @property string $bank_account_number
 * @property string $account_type
 * @property string $status
 * @property mixed $notes
 * @property int $created_at
 *
 * @package Payabbhi
 */

Class BeneficiaryAccount extends ApiResource
{
    /**
     * @param string $id The ID of the Beneficiary Account to retrieve.
     *
     * @return BeneficiaryAccount
     */
    public function retrieve($id)
    {
        return self::_retrieve($id);
    }
    
    /**
     * @param array|null $params
     *
     * @return Collection of BeneficiaryAccounts
     */
    public function all($params = null)
    {
        return self::_all($params);
    }

    /**
     * @param array|null $params.
     *
     * @return BeneficiaryAccount
     */
    public function create($params)
    {
        return self::_request(static::classUrl(), "POST", $params);
    }

}
