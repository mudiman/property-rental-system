<?php

namespace App\Repositories;

use App\Models\RoomType;

class RoomTypeRepository extends ParentRepository
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
        return RoomType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
