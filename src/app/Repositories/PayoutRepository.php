<?php

namespace App\Repositories;

use App\Models\Payout;
use Illuminate\Support\Facades\App;

class PayoutRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_method',
        'user_id',
        'holder_name',
        'card_number',
        'expire_on_month',
        'expire_on_year',
        'expiry',
        'security_code',
        'country',
        'payout_data',
        'payout_reference',
        'smoor_reference',
        'user_reference',
        'used',
        'token',
        'default',
        'invalid',
        'payment_error_type',
        'payment_error_message',
        'payment_error_code',
        'payment_error_status',
        'payment_error_param',
        'payment_response',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Payout::class;
    }
    
    public function createPaymentGatewayMethod(Payout $model)
    {
      $user = $model->user;
      $customer = \Stripe\Customer::create(array(
        "description" => $user->first_name,
        "email" => $user->email,
        "source" => $model->token,
        "metadata" => [
          "id" => $user->id,
          "first_name" => $user->first_name,
          "environment" => App::environment()
        ]
      ));
      $model->user_reference = $customer->id;
      $model->payment_response = json_encode($customer);
      $user->customer_reference = $customer->id;
      $user->save();
    }
    
    public function updatePaymentGatewayCustomer(Payout $model)
    {
      $customer = \Stripe\Customer::retrieve($model->user_reference);
      $customer->description = $model->user->first_name;
      $customer->source = $model->token; 
      $customer->save();
      $model->payment_response = json_encode($customer);
    }

  public function dispatchNotification($notificationConfig, $model, $from, $to) {
    (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $model->user->id,
        'fromUserId' => config('business.admin.id'),
      ] ))->notify();
  }

}
