<?php

namespace App\Repositories;

use App\Models\PropertyRoomType;

class PropertyRoomTypeRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'icon',
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
        return PropertyRoomType::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
