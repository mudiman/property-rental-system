<?php

namespace App\Transformers;

use App\Models\Offer;

/**
 * Class OfferIndexTransformer
 * @package namespace App\Transformers;
 */
class OfferIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(Offer $model) {
      $response = $this->transformIndexDefault($model);
      
      $response['security_deposit_amount'] = $this->formatAmount($model->security_deposit_amount);
      $response['security_holding_deposit_amount'] = $this->formatAmount($model->security_holding_deposit_amount);
      $response['rent_per_month'] = $this->formatAmount($model->rent_per_month);
      $response['rent_per_week'] = $this->formatAmount($model->rent_per_week);
      $response['rent_per_night'] = $this->formatAmount($model->rent_per_night);
      $response['rent'] = $this->formatAmount($model->rent);
      $response['checkin'] = $this->formatDate($model->checkin);
      $response['checkout'] = $this->formatDate($model->checkout);
      $response['offer_expiry'] = $this->formatDateTime($model->offer_expiry);
      $response['holding_deposit_expiry'] = $this->formatDateTime($model->holding_deposit_expiry);
      return $response;
  }

}
