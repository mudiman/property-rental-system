<?php

namespace App\Repositories;

use App\Models\Statistic;

class StatisticRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'property_id',
        'user_id',
        'view_type',
        'viewed_datetime',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Statistic::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
