<?php

namespace App\Transformers;

use App\Models\ViewingRequest;

/**
 * Class ViewingRequestIndexTransformer
 * @package namespace App\Transformers;
 */
class ViewingRequestIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(ViewingRequest $model) {
      $response = $this->transformIndexDefault($model);
      
      return $response;
  }

}
