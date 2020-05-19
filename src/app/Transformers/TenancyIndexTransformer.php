<?php

namespace App\Transformers;

use App\Models\Tenancy;

/**
 * Class TenancyIndexTransformer
 * @package namespace App\Transformers;
 */
class TenancyIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(Tenancy $model) {
      $response = $this->transformIndexDefault($model);
      
      $response['tenant_sign_datetime'] = $this->formatDateTime($model->tenant_sign_datetime);
      $response['landlord_sign_datetime'] = $this->formatDateTime($model->landlord_sign_datetime);
      $response['checkin'] = $this->formatDate($model->checkin);
      $response['checkout'] = $this->formatDate($model->checkout);
      $response['actual_checkin'] = $this->formatDateTime($model->actual_checkin);
      $response['actual_checkout'] = $this->formatDateTime($model->actual_checkout);
      $response['sign_expiry'] = $this->formatDateTime($model->sign_expiry);
      return $response;
  }

}
