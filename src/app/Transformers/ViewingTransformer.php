<?php

namespace App\Transformers;

use App\Models\Viewing;

/**
 * Class ViewingTransformer
 * @package namespace App\Transformers;
 */
class ViewingTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Viewing $model)
    {
        return [
            'id'         => (int) $model->id,
            'status'      => (string) $model->status,
            'checkin'      => boolval($model->checkin),
            'type'         => (string) $model->type,
            'start_datetime' => $this->formatDateTime($model->start_datetime),
            'end_datetime' => $this->formatDateTime($model->end_datetime),
          
            'property' => $model->property()->first($this->propertyBasicFieldList()),
            'conducted_by' => $model->conductedBy()->first($this->userBasicFieldList()),
            'viewingRequests' => $model->viewingRequests()->get($this->viewingRequestBasicFieldList()),
            'confirmRequests' => $model->confirmRequests()->get($this->viewingRequestBasicFieldList()),
            
        ] + $this->transformDefault($model);
    }
}
