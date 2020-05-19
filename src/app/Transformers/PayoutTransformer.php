<?php

namespace App\Transformers;

use App\Models\Payout;

/**
 * Class ViewingTransformer
 * @package namespace App\Transformers;
 */
class PayoutTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Payout $model)
    {
          
        return [
            'id'         => (int) $model->id,
            'payment_method'  => (string) $model->payment_method,
            'holder_name' => (string) $model->holder_name, 
            'card_number' => (string) $model->card_number,
            'expire_on_month' => (int) $model->expire_on_month,
            'expire_on_year' => (int) $model->expire_on_year,
            'expiry' => (string) $model->expiry,
            'security_code' => (string) $model->security_code,
            'country' => (string) $model->country,
            'payout_data' => (string) $model->payout_data,
            'payout_reference' => (string) $model->payout_reference,
            'smoor_reference' => (string) $model->smoor_reference,
            'user_reference' => (string) $model->user_reference,
            'token' => (string) $model->token,
            'default' => boolval($model->default),
            
            'user' => $model->user()->get($this->userBasicFieldList()),
            'transactions' => $model->transactions()->get($this->transactionBasicFieldList()),
            'lastTransaction' => $model->transactions()->latest()->first($this->transactionBasicFieldList()),
            'transactionsSum' => $this->formatAmount($model->transactionsSum),
            'lastRent' => $this->formatAmount($model->lastRent),
            
        ] + $this->transformDefault($model);
    }
}