<?php

namespace App\Repositories;

use App\Models\LettingType;

class LettingTypeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'icon',
        'is_active',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LettingType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
