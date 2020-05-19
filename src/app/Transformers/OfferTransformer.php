<?php

namespace App\Transformers;

use App\Models\Offer;

/**
 * Class OfferTransformer
 * @package namespace App\Transformers;
 */
class OfferTransformer extends BaseTransformer {

  /**
   * Transform the \Tenancy entity
   * @param \Tenancy $model
   *
   * @return array
   */
  public function transform(Offer $model) {
    
    return [
      'id' => (int) $model->id,
      'checkin' => $this->formatDate($model->checkin),
      'checkout' => $this->formatDate($model->checkout),
      'thread' => $model->thread,
      'type' => $model->type,
      'status' => $model->status,
      'landlord_id' => $model->landlord_id,
      'tenant_id' => $model->tenant_id,
      'property_id' => $model->property_id,
      'property_pro_id' => $model->property_pro_id,
      'currency' => $model->currency,
      'payout_id' => $model->payout_id,
      'landlord_payin_id' => $model->landlord_payin_id,
      'offer_expiry' => $this->formatDateTime($model->offer_expiry),
      'holding_deposit_expiry' => $this->formatDateTime($model->holding_deposit_expiry),
      'security_deposit_week' => $model->security_deposit_week,
      'security_deposit_amount' => $this->formatAmount($model->security_deposit_amount),
      'security_holding_deposit_amount' => $this->formatAmount($model->security_holding_deposit_amount),
      'rent_per_month' => $this->formatAmount($model->rent_per_month),
      'rent_per_week' => $this->formatAmount($model->rent_per_week),
      'rent_per_night' => $this->formatAmount($model->rent_per_night),
      'rent' => $this->formatAmount($model->rent),
      'special_condition' => $model->special_condition,
      
      'tenant' => $model->tenant()->first($this->userBasicFieldList()),
      'propertyPro' => $model->propertyPro()->get($this->userBasicFieldList()),
      'property' => $model->property()->first($this->propertyBasicFieldList()),
      'landlord' => $model->landlord()->first($this->userBasicFieldList()),
      'tenancy' => $model->tenancy()->first(),
      'previousOffer' => $model->previousOffer()->first($this->offerBasicFieldList()),
      
      'events' => $model->events()->get(),
      'reviews' => $model->reviews()->get(),
      'messages' => $model->messages()->get(),
      'documents' => $model->documents()->orderBy('created_at')->get($this->documentBasicFieldList()),
      
    ] + $this->transformDefault($model);
  }

}