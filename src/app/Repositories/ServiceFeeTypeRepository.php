<?php

namespace App\Repositories;

use App\Models\ServiceFeeType;

class ServiceFeeTypeRepository extends ParentRepository
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
        return ServiceFeeType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
