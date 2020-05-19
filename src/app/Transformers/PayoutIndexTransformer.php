<?php

namespace App\Transformers;

use App\Models\Payout;

/**
 * Class ViewingTransformer
 * @package namespace App\Transformers;
 */
class PayoutIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Payout $model)
    {
      $response = $this->transformIndexDefault($model);
      $response['transactionsSum'] = $this->formatAmount($model->transactionsSum);
      $response['lastRent'] = $this->formatAmount($model->lastRent);
      return $response;
    }
}
            