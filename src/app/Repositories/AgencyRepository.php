<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'payin_id',
        'name',
        'status',
        'commission',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Agency::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
