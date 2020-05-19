<?php

namespace App\Transformers;

use App\Models\Payin;

/**
 * Class ViewingTransformer
 * @package namespace App\Transformers;
 */
class PayinIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Payin $model)
    {
      $response = $this->transformIndexDefault($model);
      $response['date_of_birth'] = $this->formatDate($model->date_of_birth);
      $response['transactionsSum'] = $this->formatAmount($model->transactionsSum);
      $response['currentMonthLandlordPayout'] = $this->formatAmount($model->currentMonthLandlordPayout);
      $response['currentMonthPropertyProPayout'] = $this->formatAmount($model->currentMonthPropertyProPayout);
      $response['newPropertyAmount'] = $this->formatAmount($model->newPropertyAmount);
      return $response;
          
    }
}