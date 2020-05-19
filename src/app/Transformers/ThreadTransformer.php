<?php

namespace App\Transformers;

use App\Models\Thread;

/**
 * Class ThreadTransformer
 * @package namespace App\Transformers;
 */
class ThreadTransformer extends BaseTransformer
{

    /**
     * Transform the \Viewing entity
     * @param \Viewing $model
     *
     * @return array
     */
    public function transform(Thread $model)
    {
        return [
            'id'         => (int) $model->id,
            'title'         => (string) $model->title,
            'status'      => (string) $model->status,
          
            'messages' => $model->messages()->limit(10)->offset(0)->orderBy('created_at')->get(),
            'participantUsers' => $model->participantUsers()->get($this->userBasicFieldList()),
            
        ] + $this->transformDefault($model);
    }
}
