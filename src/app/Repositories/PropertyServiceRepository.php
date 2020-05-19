<?php

namespace App\Repositories;

use App\Models\PropertyService;

class PropertyServiceRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'property_pro_entity_id',
        'user_id',
        'property_id',
        'service_id',
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
        return PropertyService::class;
    }
    
    public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
    }
}
