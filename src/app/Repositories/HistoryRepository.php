<?php

namespace App\Repositories;

use App\Models\History;

class HistoryRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'historiable_id',
        'historiable_type',
        'snapshot',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return History::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
