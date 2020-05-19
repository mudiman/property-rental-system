<?php

namespace App\Transformers;

use App\Models\Viewing;

/**
 * Class ViewingIndexTransformer
 * @package namespace App\Transformers;
 */
class ViewingIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(Viewing $model) {
      $response = $this->transformIndexDefault($model);
      
      $response['start_datetime'] = $this->formatDateTime($model->start_datetime);
      $response['end_datetime'] = $this->formatDateTime($model->end_datetime);
      return $response;
  }

}
