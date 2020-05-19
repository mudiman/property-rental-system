<?php

namespace App\Transformers;

use App\Models\User;

/**
 * Class UserIndexTransformer
 * @package namespace App\Transformers;
 */
class UserIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model) {
      $response = $this->transformIndexDefault($model);
      
      $response['date_of_birth'] = $this->formatDateTime($model->date_of_birth);
      $response['email_verification_code_expiry'] = $this->formatDateTime($model->email_verification_code_expiry);
      $response['available_to_move_on'] = $this->formatDate($model->available_to_move_on);
      $response['current_contract_start_date'] = $this->formatDate($model->current_contract_start_date);
      $response['current_contract_end_date'] = $this->formatDate($model->current_contract_end_date);
      return $response;
  }

}
