<?php

namespace App\Repositories;

use App\Models\PremiumListing;

class PremiumListingRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'property_id',
        'end_datetime',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PremiumListing::class;
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    
  }

}
