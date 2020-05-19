<?php

namespace App\Repositories;

use App\Models\PropertyPro;

class PropertyProRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'thread',
        'landlord_id',
        'property_pro_id',
        'property_id',
        'property_pro_payin_id',
        'property_pro_sign_location',
        'property_pro_sign_datetime',
        'landlord_sign_location',
        'landlord_sign_datetime',
        'price_type',
        'price',
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
        return PropertyPro::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'tenant_first_name' => $model->tenant->first_name,
        'landlord_first_name' => $model->landlord->first_name,
        'propertypro_first_name' => isset($model->property_pro_id) ? $model->propertyPro->first_name: '',
        'property_title' => $model->property->title,
        'price_type' => $model->price_type,
        'price' => $model->price,
        'messageId' => $model->id,
        'messageType' => PropertyPro::morphClass,
        'snapshot' => json_encode($model->toArray())
      ]))->notify();
  }

}
