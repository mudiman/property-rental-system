<?php

namespace App\Transformers;

use App\Models\Tenancy;

/**
 * Class TenancyTransformer
 * @package namespace App\Transformers;
 */
class TenancyTransformer extends BaseTransformer {

  /**
   * Transform the \Tenancy entity
   * @param \Tenancy $model
   *
   * @return array
   */
  public function transform(Tenancy $model) {
    return [
      'id' => (int) $model->id,
      'checkin' => $this->formatDate($model->checkin),
      'checkout' => $this->formatDate($model->checkout),
      'actual_checkin' => $this->formatDateTime($model->actual_checkin),
      'actual_checkout' => $this->formatDateTime($model->actual_checkout),
      'due_date' => $this->formatDate($model->due_date),
      'due_amount' => $this->formatAmount($model->due_amount),
      'thread' => $model->thread,
      'type' => $model->type,
      'status' => $model->status,
      'payout_id' => $model->payout_id,
      'landlord_payin_id' => $model->landlord_payin_id,
      'sign_expiry' => $this->formatDateTime($model->sign_expiry),
      'special_condition' => $model->special_condition,
      
      'tenant_sign_location' => $model->tenant_sign_location,
      'tenant_sign_datetime' => $this->formatDateTime($model->tenant_sign_datetime),
      'landlord_sign_location' => $model->landlord_sign_location,
      'landlord_sign_datetime' => $this->formatDateTime($model->landlord_sign_datetime),
      
      'tenant' => $model->tenant()->first($this->userBasicFieldList()),
      'propertyPro' => $model->propertyPro()->get($this->userBasicFieldList()),
      'property' => $model->property()->first($this->propertyBasicFieldList()),
      'landlord' => $model->landlord()->first($this->userBasicFieldList()),
      'offer' => $model->offer()->first($this->offerBasicFieldList()),
      'parentTenancy' => $model->parentTenancy()->first($this->tenancyBasicFieldList()),
      
      'transactionFirstRent' => $model->transactionFirstRent($this->transactionBasicFieldList()),
      'transactionLandlordSecurityDeposit' => $model->transactionLandlordSecurityDeposit($this->transactionBasicFieldList()),
      'transactionCurrentMonthRent' => $model->transactionCurrentMonthRent($this->transactionBasicFieldList()),
      'transactionsDue' => $model->transactionsDue($this->transactionBasicFieldList()),
      
      'events' => $model->events()->get(),
      'reviews' => $model->reviews()->get(),
      'messages' => $model->messages()->get(),
      'documents' => $model->documents()->orderBy('created_at')->get($this->documentBasicFieldList()),
      'documentTenant' => $model->documentTenant()->first($this->documentBasicFieldList()),
      'documentLandlord' => $model->documentLandlord()->first($this->documentBasicFieldList()),
      
    ] + $this->transformDefault($model);
  }

}
