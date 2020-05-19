<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository extends ParentRepository
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
        return PaymentMethod::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
