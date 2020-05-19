<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'created_by',
        'title',
        'type',
        'description',
        'area',
        'lower_cap',
        'upper_cap',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Service::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
