<?php 

namespace App\Observers;

use App\Models\Tenancy;
use App\Models\Offer;
use App\Models\Property;
use App\Repositories\TenancyRepository;
use App\Repositories\OfferRepository;
use Carbon\Carbon;
use App\Support\Helper;

class TenancyObserver extends ParentObserver
{

    protected $tenancyRepository;
    protected $offerRepository;
    
    public function __construct() 
    {
      parent::__construct();
      $this->tenancyRepository = \App::make(TenancyRepository::class);
      $this->offerRepository = \App::make(OfferRepository::class);
    }
    
    public function deleted(Tenancy $model)
    {
        $model->messages()->delete();
        $model->documents()->delete();
        
        $offer = isset($model->offer) ? $model->offer: $model->offer();
        $offer->delete();
        
        if (isset($model->parent_tenancy_id)) {
          $tenancy = isset($model->parentTenancy) ? $model->parentTenancy: $model->parentTenancy();
          $tenancy->delete();
        }
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
        foreach(isset($model->reviews) ? $model->reviews: $model->reviews() as $review) {
          $review->delete();
        }
        foreach(isset($model->transactions) ? $model->transactions: $model->transactions() as $transaction) {
          $transaction->delete();
        }
        
        if (isset($model->property) 
            && $model->property->status == Property::STATUS_OCCUPIED
            && $model->checkout >= Carbon::now()
            && $model->checkin < Carbon::now()) {
          $property = $model->property;
          $property->status = Property::STATUS_DRAFT;
          $property->save();
          
          $to = $this->offerRepository->getAllStalkHolderIds($model->offer, [$model->updated_by]);
          $this->tenancyRepository->dispatchNotification('tenancy.delete', $model, $model->updated_by, $to);
        }
    }

    public function restored(Tenancy $model)
    {
        $model->messages()->restore();
        $model->documents()->restore();
        
        $offer = isset($model->offer) ? $model->offer: $model->offer();
        $offer->restore();
        
        if (isset($model->parent_tenancy_id)) {
          $tenancy = isset($model->parentTenancy) ? $model->parentTenancy: $model->parentTenancy();
          $tenancy->restore();
        }
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->restore();
        }
        foreach(isset($model->reviews) ? $model->reviews: $model->reviews() as $review) {
          $review->restore();
        }
        foreach(isset($model->transactions) ? $model->transactions: $model->transactions() as $transaction) {
          $transaction->restore();
        }
    }
    
    public function updating(Tenancy $model)
    {
        $original = $model->getOriginal();
        // store old status
        if ($original['status'] != $model->status
            || empty($model->previous_status)) {
          $model->previous_status = $original['status'];
        }
        
        if ($original['status'] == Tenancy::PRE_NOTICE
            && $model->status == Tenancy::NOTICE) {
          if (config('business.tenancy.notice.duration') == 0) {
            $model->actual_checkout = Carbon::now()->addMonths(2);
          } else {
            $model->actual_checkout = Carbon::now()->addDays(config('business.tenancy.notice.duration'));
          }
        }
        if ($original['status'] == Tenancy::ROLLING
            && $model->status == Tenancy::NOTICE) {
          if (config('business.tenancy.notice.duration') == 0) {
            $model->actual_checkout = Carbon::now()->addMonths(1);
          } else {
            $model->actual_checkout = Carbon::now()->addDays(config('business.tenancy.rolling.notice_duration'));
          }
        }
        
        if ($original['status'] == Tenancy::PRESIGN
            && $model->isTenantSigned()
            && $model->isLandlordSigned()) {
          $model->status = Tenancy::SIGNING_COMPLETE;
          $transaction = $this->tenancyRepository->makeAdvanceFirstRentTransaction($model, false);
          if ($transaction) {
            $model->due_date = $transaction->due_date->addMonths(1);
            $model->due_amount = $transaction->amount;
          }
          $this->tenancyBindedNotificationAndRecordReminder($model);
        }
    }
    
    public function updated(Tenancy $model)
    {
        $original = $model->getOriginal();
        if ($original['status'] != $model->status
            && $model->status == Tenancy::CANCEL) {
          $this->tenancyExpiredNotification($model);
        }
        // tenancy cancel
        if ($original['status'] != $model->status
            && $model->status == Tenancy::CANCEL
            && !empty($model->updated_by)) {
          $this->tenancyForceCancelNotification($model);
        }
        
        if ($original['status'] != $model->status
            && $model->status == Tenancy::SIGNING_COMPLETE) {
          $offer = $model->offer;
          if ($offer->type == Offer::TYPE_RENEW 
              && $offer->status == Offer::ACCEPT) {
            $offer->status = Offer::ACCEPT_RENEWED;
            $offer->save();
          }
        }
        
        if ($original['status'] != $model->status
            && $model->status == Tenancy::START) {
          $this->afterTransitionstart($model);
        }
        
        if ($original['status'] != $model->status
            && $model->status == Tenancy::COMPLETE) {
          $model->property->status = Property::STATUS_LIVE;
          $model->property->save();
          $model->tenant->available_to_move_on = $model->actual_checkout;
          $model->tenant->save();
        }
        // send checkin notification
        if (empty($original['actual_checkin']) && !empty($model->actual_checkin)) {
          $to = $this->offerRepository->getAllStalkHolderIds($model->offer, [$model->updated_by]);
          $this->tenancyRepository->dispatchNotification('tenancy.checkin', $model, $model->updated_by, $to);
        }
        
        if (($original['status'] == Tenancy::PRE_NOTICE || $original['status'] == Tenancy::ROLLING)
            && $model->status == Tenancy::NOTICE) {
          $this->tenancyNoticeNotification($model);
        }
        
        if ($original['status'] != $model->status
            && $model->status == Tenancy::PRE_NOTICE) {
          $this->tenancyRepository->dispatchNotification('tenancy.prenotice_tenant', $model, $model->updated_by, $model->tenant->id);
        }
        
        if ($original['status'] != $model->status
            && $model->status == Tenancy::ROLLING) {
          $this->tenancyRollingNotification($model);
        }
    }


    public function created(Tenancy $model)
    {
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->recordEvent('tenancy.offer_sign_expiry', $model, $to, $model->toArray() + ['property_title' => $model->property->title], $model->sign_expiry);
      $this->recordEvent('tenancy.move_in', $model, $to, $model->toArray() + ['property_title' => $model->property->title], $model->checkin);
      $this->recordEvent('tenancy.move_out', $model, $to, $model->toArray() + ['property_title' => $model->property->title], $model->checkout);
      
    }
    
    private function tenancyBindedNotificationAndRecordReminder($model)
    {
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->tenancyRepository->dispatchNotification('tenancy.bind', $model, config('business.admin.id'), $to);
      
      $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_bind'), 
          1, Helper::calculateFactorTime($model->sign_expiry, $model->tenant_sign_datetime)[1]);
      $this->recordScore($model, $model->landlord->id, 
          config('business.scoring.default_type.tenancy_bind'),
          1, Helper::calculateFactorTime($model->sign_expiry, $model->landlord_sign_datetime)[1]);
      
      $now = Carbon::now();
      $diffInDays = $model->checkin->diffInDays($now);
      switch (true) {
        case $diffInDays  > 30:
          $this->recordEvent('tenancy.first_rent_security_reminder', $model, $model->tenant_id, $model->toArray() + ['property_title' => $model->property->title], 
              $model->checkin->subDays(30));
          $this->recordEvent('tenancy.first_rent_security_final_reminder', $model, $model->tenant_id, $model->toArray() + ['property_title' => $model->property->title], 
              $model->checkin->subDays(28));
          break;
        case $diffInDays  < 30 && $diffInDays  > 3:
          $this->recordEvent('tenancy.first_rent_security_reminder', $model, $model->tenant_id, $model->toArray() + ['property_title' => $model->property->title], 
              $now->addDays(1));
          $this->recordEvent('tenancy.first_rent_security_final_reminder', $model, $model->tenant_id, $model->toArray() + ['property_title' => $model->property->title], 
              $now->addDays(2));
          break;
        case $diffInDays  < 3:
          $this->recordEvent('tenancy.first_rent_security_reminder', $model, $model->tenant_id, $model->toArray() + ['property_title' => $model->property->title], 
              $now->addDays(1));
          break;
      }
      
    }
    
    private function tenancyExpiredNotification($model)
    {
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->tenancyRepository->dispatchNotification('tenancy.cancel', $model, config('business.admin.id'), $to);
      
      if (!$model->isTenantSigned()) {
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_cancel'), 
          0, 1);
      }
      if (!$model->isLandlordSigned()) {
        $this->offerRepository->refundInitialDeposit($model->offer);
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_cancel'), 
          0, 1);
      }
      $this->cancelOfferAndUpdateParentTenancy($model);
    }
    
    private function tenancyForceCancelNotification($model)
    {
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->tenancyRepository->dispatchNotification('tenancy.cancel', $model, $model->updated_by, $to);
      
      if ($model->updated_by == $model->tenant->id) {
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_cancel'), 
          0, 1);
      } elseif ($model->updated_by == $model->landlord->id) {
        $this->offerRepository->refundInitialDeposit($model->offer);
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_cancel'), 
          0, 1);
      }
      
      $this->cancelOfferAndUpdateParentTenancy($model);
    }
    
    private function tenancyNoticeNotification($model)
    {
      $tenant = $model->tenant;
      $property = $model->property;
      
      $tenant->available_to_move_on = $model->checkout;
      $tenant->save();
      $this->tenancyRepository->dispatchNotification('tenancy.notice', $model, $model->landlord->id, $model->tenant->id);
      $this->recordScore($model, $model->landlord->id, 
          config('business.scoring.default_type.tenancy_bind'), 
          1, 1);
    }
    
    private function tenancyRollingNotification($model)
    {
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->tenancyRepository->dispatchNotification('tenancy.rolling', $model, config('business.admin.id'), $to);
      $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_bind'), 
          0, 1);
      $this->recordScore($model, $model->landlord->id, 
          config('business.scoring.default_type.tenancy_bind'), 
          0, 1);
    }
    
    private function afterTransitionstart($model) {
      
      $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
      $this->tenancyRepository->dispatchNotification('tenancy.start', $model, config('business.admin.id'), $to);
      
      if (in_array($model->type, [Offer::TYPE_LONG, Offer::TYPE_MID])) {
        $tenant = $model->tenant;
        $property = $model->property;
        
        $property->status = Property::STATUS_OCCUPIED;
        
        $tenant->current_residence_postcode = $property->postcode;
        $tenant->current_residence_property_type = $property->property_type;
        $tenant->current_residence_bedrooms = $property->bedrooms;
        $tenant->current_residence_bathrooms = $property->bathrooms;
        $tenant->address = $property->street;
        
        $tenant->current_contract_type = $model->type;
        $tenant->current_contract_start_date = $model->checkin;
        $tenant->current_contract_end_date = $model->checkout;
        
        $tenant->current_rent_per_month = $model->offer->rent_per_month;
        
        $tenant->available_to_move_on = $model->checkout;
        
        $property->save();
        $tenant->save();
        // Set final Renew state on its parent tenancy if exist
        if (isset($model->parent_tenancy_id)) {
          $parentTenancy = $model->parentTenancy;
          $parentTenancy->status = Tenancy::RENEWED;
          $parentTenancy->save();
        }
      }
      $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.tenancy_bind'), 
          1, 1);
    }
    
    private function cancelOfferAndUpdateParentTenancy($model) {
      $offer = $model->offer;
      $offer->status = Offer::CANCEL;
      $offer->save();
      
      if ($model->offer->type == Offer::TYPE_RENEW) {
        $parentTenancy = $model->parentTenancy;
        $parentTenancy->status = $parentTenancy->previous_status;
        $parentTenancy->save();
        $message = strtolower($parentTenancy->previous_status);
        if ($parentTenancy->previous_status == Tenancy::PRE_NOTICE) {
          $message = 'prenotice_again';
        }
        $to = $this->offerRepository->getAllStalkHolderIds($model->offer);
        $this->tenancyRepository->dispatchNotification("tenancy.$message", $model, $model->updated_by, $to);
      }
    }
}