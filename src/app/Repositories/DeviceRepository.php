<?php

namespace App\Repositories;

use App\Models\Device;

class DeviceRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'api_version',
        'user_id',
        'token_id',
        'type',
        'device_id',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Device::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
