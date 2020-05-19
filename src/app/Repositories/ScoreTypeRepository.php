<?php

namespace App\Repositories;

use App\Models\ScoreType;

class ScoreTypeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'category',
        'roles',
        'min_percentage',
        'max_percentage',
        'weight',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ScoreType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
