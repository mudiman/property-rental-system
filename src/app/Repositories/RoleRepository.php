<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends ParentRepository
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
        return Role::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
