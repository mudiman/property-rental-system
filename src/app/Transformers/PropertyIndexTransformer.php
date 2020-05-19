<?php

namespace App\Transformers;

use App\Models\Property;

/**
 * Class PropertyIndexTransformer
 * @package namespace App\Transformers;
 */
class PropertyIndexTransformer extends BaseTransformer
{

    /**
     * Transform the \Property entity
     * @param \Property $model
     *
     * @return array
     */
    public function transform(Property $model)
    {
      $response = $this->transformIndexDefault($model);
      $response['available_date'] = $this->formatDate($model->available_date);
      
      $response['has_balcony_terrace'] = boolval($model->has_balcony_terrace);
      $response['has_parking'] = boolval($model->has_parking);
      $response['has_garden'] = boolval($model->has_garden);
      $response['flatshares'] = boolval($model->flatshares);
      $response['reviewed'] = boolval($model->reviewed);
      $response['ensuite'] = boolval($model->ensuite);
      $response['quick_booking'] = boolval($model->quick_booking);
      
      
      return $response;
    }
}
