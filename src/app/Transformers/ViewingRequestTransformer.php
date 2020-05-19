<?php

namespace App\Transformers;

use App\Models\ViewingRequest;

/**
 * Class ViewingRequestTransformer
 * @package namespace App\Transformers;
 */
class ViewingRequestTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(ViewingRequest $model)
    {
        return [
            'id'         => (int) $model->id,
            'viewing_id'      => $model->viewing_id,
            'view_by_user'         => $model->view_by_user,
            'checkin'         => boolval($model->checkin),
            'status'         => (string) $model->status,
          
            'viewByUser' => $model->viewByUser()->first($this->userBasicFieldList()),
            'viewing' => $model->viewing()->first($this->viewingBasicFieldList()),
            'messages' => $model->messages()->get(),
            'events' => $model->events()->get(),
            
        ] + $this->transformDefault($model);
    }
}