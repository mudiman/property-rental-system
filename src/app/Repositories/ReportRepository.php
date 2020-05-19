<?php

namespace App\Repositories;

use App\Models\Report;

class ReportRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'by_user',
        'comment',
        'reportable_id',
        'reportable_type',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Report::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
