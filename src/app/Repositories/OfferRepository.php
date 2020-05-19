<?php

namespace App\Repositories;

use App\Models\Offer;
use App\Models\Transaction;
use Propaganistas\LaravelIntl\Facades\Currency;

class OfferRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'thread',
        'tenant_id',
        'property_id',
        'landlord_id',
        'property_pro_id',
        'previous_offer_id',
        'payout_id',
        'landlord_payin_id',
        'status',
        'type',
        'offer_expiry',
        'holding_deposit_expiry',
        'checkin',
        'checkout',
        'rent_per_month',
        'rent_per_week',
        'rent_per_night',
        'currency',
        'security_deposit_week',
        'security_deposit_amount',
        'security_holding_deposit_amount',
        'special_condition',
        'updated_by',
        'created_by',
        'deleted_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Offer::class;
    }
    
    public function getAllStalkHolderIds($model, $butIds = [])
    {
      $to = [];
      if (!in_array($model->tenant->id, $butIds)) {
        $to[] =  $model->tenant->id;
      }
      if (!empty($model->landlord_id) 
          && !in_array($model->landlord_id, $butIds)) {
        $to[] =  $model->landlord_id;
      }
      if (!empty($model->property_pro_id)
          && !in_array($model->property_pro_id, $butIds)) {
        $to[] = $model->property_pro_id;
      }
      foreach ($model->property->propertyProAcceptedRequests as $propertyProAcceptedRequest) {
        if (!empty($propertyProAcceptedRequest->property_pro_id)) {
          $to[] = $propertyProAcceptedRequest->property_pro_id;
        }
      }
      return $to;
    }
    
    public function refundInitialDeposit($model)
    {
      $transactionRepo =  \App::make(TransactionRepository::class);
      $transaction = Transaction::where('status', Transaction::STATUS_DONE)
          ->where('title', Transaction::TITLE_INITIAL_DEPOSIT)
          ->where('transactionable_id', $model->id)
          ->where('transactionable_type', Offer::morphClass)
          ->first();
      if (!empty($transaction)) {
        return $transactionRepo->refundTransaction($transaction);
      }
      return null;
    }
    
    public function dispatchNotification($notificationConfig, $model, $from, $to)
    {
      $created_by_first_name = $model->tenant->first_name;
      if ($model->created_by == $model->landlord_id) {
        $created_by_first_name = $model->landlord->first_name;
      } elseif(isset($model->property_pro_id) 
          && $model->created_by == $model->property_pro_id) {
        $created_by_first_name = $model->propertyPro->first_name;
      }
      
      $updated_by_first_name = $created_by_first_name;
      if (!empty($model->updated_by)) {
        $updated_by_first_name = $model->tenant->first_name;
        if ($model->updated_by == $model->landlord_id) {
          $updated_by_first_name = $model->landlord->first_name;
        } elseif($model->updated_by == $model->property_pro_id) {
          $updated_by_first_name = $model->propertyPro->first_name;
        }
      }
      
      (new \App\Support\Notification($notificationConfig, [
        'toUserId' => $to,
        'fromUserId' => $from,
        'tenant_first_name' => $model->tenant->first_name,
        'landlord_first_name' => $model->landlord->first_name,
        'created_by_first_name' => $created_by_first_name,
        'updated_by_first_name' => $updated_by_first_name,
        'propertypro_first_name' => isset($model->property_pro_id) ? $model->propertyPro->first_name: '',
        'arbitrator_first_name' => isset($model->property_pro_id) ? $model->propertyPro->first_name: $model->landlord->first_name,
        'property_title' => $model->property->title,
        'messageId' => $model->id,
        'messageType' => Offer::morphClass,
        'currency' => $model->currency,
        'offer_amount' => $model->rent,
        'format_amount' => isset($model->rent) ?
          Currency::format($model->rent, strtoupper($model->currency)):'',
        'security_holding_deposit_amount' => isset($model->security_holding_deposit_amount) ? 
          Currency::format($model->security_holding_deposit_amount, strtoupper($model->currency)):'',
        'security_deposit_amount' => isset($model->security_deposit_amount) ? 
          Currency::format($model->security_deposit_amount, strtoupper($model->currency)):'',
        'snapshot' => json_encode($model->toArray())
      ] ))->notify();
    }
}
