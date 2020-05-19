<?php

namespace App\Transformers;

use App\Models\Like;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class LikeTransformer extends BaseTransformer {

  /**
   * Transform the \Tenancy entity
   * @param \Tenancy $model
   *
   * @return array
   */
  public function transform(Like $model) {
    
    return [
      'id' => (int) $model->id,
      'user_id' => $model->user_id,
      'likeable_id' => $model->likeable_id,
      'likeable_type' => $model->likeable_type,
      'type' => $model->type,
      
      'likeable' => $model->likeable()->first(),
      
    ] + $this->transformDefault($model);
  }

}