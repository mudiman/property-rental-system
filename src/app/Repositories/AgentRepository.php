<?php

namespace App\Repositories;

use App\Models\Agent;

class AgentRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'agency_id',
        'user_id',
        'status',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Agent::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
