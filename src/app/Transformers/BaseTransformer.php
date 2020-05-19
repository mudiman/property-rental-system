<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Tenancy;
use App\Support\Helper;
use Carbon\Carbon;
/**
 * Class TenancyIndexTransformer
 * @package namespace App\Transformers;
 */
class BaseTransformer extends TransformerAbstract
{
  
  public function transformIndexDefault($model)
  {
    $response = $model->toArray();
    return $response;
  }
  
  public function transformDefault($model)
  {
    return [
      'created_by' => $model->created_by,
      'updated_by' => $model->updated_by,
      'deleted_by' => $model->deleted_by,
    ];
  }
  
  protected function formatDateTime($dateTime)
  {
      return isset($dateTime) ? $dateTime->toIso8601String(): null;
  }
  
  protected function formatDate($date)
  {
      return isset($date) ? $date->format("Y-m-d"): null;
  }
  
  protected function formatAmount($amount)
  {
    return Helper::costFormater($amount);
  }
  
  protected function userBasicFieldList()
  {
    return ['users.id', 'username', 'first_name', 'email', 'profile_picture'];
  }
  
  protected function propertyBasicFieldList()
  {
    return ['properties.id', 'title', 'postcode', 'street', 'city','cordinate', 'profile_picture'];
  }
  
  protected function documentBasicFieldList()
  {
    return ['documents.id', 'type', 'name', 'documentable_type', 'filename', 'file_front_filename', 'file_back_filename'];
  }
  
  protected function imageBasicFieldList()
  {
    return ['id', 'path', 'type'];
  }
  
  protected function offerBasicFieldList()
  {
    return ['offers.id', 'thread', 'status', 'type', 'checkin', 'checkout', 'rent_per_month', 'rent_per_week', 'rent_per_night'];
  }
  
  protected function tenancyBasicFieldList()
  {
    return ['tenancies.id', 'thread', 'status', 'type', 'actual_checkin', 'actual_checkout'];
  }
  
  protected  function reviewBasicFieldList()
  {
    return ['reviews.id', 'by_user', 'comment', 'rating'];
  }
  
  protected function reportBasicFieldList()
  {
    return ['reports.id', 'comment', 'reportable_id', 'reportable_type'];
  }
  
  protected function viewingBasicFieldList()
  {
    return ['viewings.id', 'property_id', 'conducted_by', 'start_datetime', 'end_datetime', 'type', 'status', 'checkin'];
  }
  
  protected function viewingRequestBasicFieldList()
  {
    return ['viewing_requests.id', 'viewing_id', 'view_by_user', 'status', 'checkin'];
  }
  
  protected function deviceBasicFieldList()
  {
    return ['devices.id', 'device_id', 'api_version'];
  }
  
  protected function serviceBasicFieldList()
  {
    return ['services.id', 'title', 'description', 'area', 'lower_cap', 'upper_cap'];
  }
  
  protected function userServiceBasicFieldList()
  {
    return ['user_services.id', 'service_id', 'pricing', 'price', 'extra_charges'];
  }
  
  protected function propertyProOfferBasicFieldList()
  {
    return ['property_pros.id', 'landlord_id', 'property_id', 'status', 'price_type', 'price'];
  }
  
  protected function referenceBasicFieldList()
  {
    return ['references.id', 'by_user', 'comment'];
  }
  
  protected function transactionBasicFieldList()
  {
    return ['transactions.id','payin_id', 'payout_id', 'parent_transaction_id', 'transactionable_id', 'transactionable_type', 'type','amount', 'currency'
      , 'smoor_commission', 'payment_gateway_commission', 'landlord_commission', 'property_pro_commission', 'agency_commission'
      , 'status', 'title', 'description', 'payment_error_message', 'payment_error_status'];
  }
        
}