<?php

namespace App\Transformers;

use App\Models\Payin;

/**
 * Class ViewingTransformer
 * @package namespace App\Transformers;
 */
class PayinTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Payin $model)
    {
        return [
            'id'         => (int) $model->id,
            'user_id'  => (string) $model->user_id,
            'bank_name' => (string) $model->bank_name, 
            'account_number' => (string) $model->account_number,
            'routing_number' => (string) $model->routing_number,
            'currency' => (string) $model->currency,
            'iban' => (string) $model->iban,
            'countryCode' => (string) $model->countryCode,
            'sort_code' => (string) $model->sort_code,
            'bic' => (string) $model->bic,
            'ip' => (string) $model->ip,
            'first_name' => (string) $model->first_name,
            'last_name' => (string) $model->last_name,
            'phone' => (string) $model->phone,
            'email' => (string) $model->email,
            'gender' => (string) $model->gender,
            'date_of_birth' => $this->formatDate($model->date_of_birth),
            'ssn' => (string) $model->ssn,
            'address' => (string) $model->address,
            'legal_name' => (string) $model->legal_name,
            'tax_id' => (string) $model->tax_id,
            'locality' => (string) $model->locality,
            'postal_code' => (string) $model->postal_code,
            'region' => (string) $model->region,
            'entity_type' => (string) $model->entity_type,
            'billing_address' => (string) $model->billing_address,
            'payin_data' => (string) $model->payin_data,
            'reference' => (string) $model->reference,
            'token' => (string) $model->token,
            'verified' => boolval($model->verified),
            'default' => boolval($model->default),
            'token' => (string) $model->token,
            'verification_response' => isset($model->verification_response) ? json_decode($model->verification_response): null,
            
            'user' => $model->user()->get($this->userBasicFieldList()),
            'transactions' => $model->transactions()->get($this->transactionBasicFieldList()),
            'transactionsSum' => $this->formatAmount($model->transactionsSum),
            'currentMonthLandlordPayout' => $this->formatAmount($model->currentMonthLandlordPayout),
            'currentMonthPropertyProPayout' => $this->formatAmount($model->currentMonthPropertyProPayout),
            'newPropertyAmount' => $this->formatAmount($model->newPropertyAmount),
            
        ] + $this->transformDefault($model);
    }
}