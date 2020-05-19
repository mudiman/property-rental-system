<?php

namespace App\Repositories;

use App\Models\Property;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PropertyRepository extends ParentRepository
{
  use DispatchesJobs;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'landlord_id',
        'profile_picture',
        'reference',
        'title',
        'summary',
        'letting_type',
        'property_type',
        'room_type',
        'room_suitable',
        'bathroom_type',
        'people_living',
        'status',
        'completion_phase',
        'available_date' => '>=',
        'cordinate',
        'postcode' => 'like',
        'door_number' => 'like',
        'street' => 'like',
        'city' => 'like',
        'verified',
        'apartment_building',
        'floors',
        'floor',
        'county',
        'country',
        'currency',
        'rent_per_month',
        'rent_per_week' => '<=',
        'rent_per_night' => '<=',
        'minimum_accepted_price' => '>=',
        'minimum_accepted_price_short_term_price' => '>=',
        'security_deposit_weeks',
        'security_deposit_amount',
        'security_deposit_holding_amount',
        'contract_length_months',
        'shortterm_rent_per_month',
        'shortterm_rent_per_week',
        'valuation_comment' => 'like',
        'valuation_rating',
        'quick_booking',
        'area_overview' => 'like',
        'area_info' => 'like',
        'notes' => 'like',
        'rules' => 'like',
        'getting_around' => 'like',
        'receptions' => '>=',
        'bedrooms' => '>=',
        'bathrooms' => '>=',
        'has_garden',
        'has_balcony_terrace',
        'has_parking',
        'ensuite',
        'flatshares',
        'reviewed',
        'total_listing_view',
        'total_detail_view',
        'view_history',
        'extra_info',
        'inclusive',
        'parent_property_id',
        'data',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Property::class;
    }
    
    public function dispatchNotification($notificationConfig, $model, $from, $to)
    {
      (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'tenant_first_name' => $model->tenant->first_name,
        'landlord_first_name' => $model->landlord->first_name,
        'property_title' => $model->title,
        'currency' => $model->currency,
        'offer_amount' => $model->rent,
        'messageId' => $model->id,
        'messageType' => Property::morphClass,
        'snapshot' => json_encode($model->toArray())
      ] + $model->toArray()))->notify();
    }
}
