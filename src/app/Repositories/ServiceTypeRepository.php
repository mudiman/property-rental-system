<?php

namespace App\Repositories;

use App\Models\ServiceType;

class ServiceTypeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
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
        return ServiceType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
