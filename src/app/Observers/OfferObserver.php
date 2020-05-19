<?php 

namespace App\Observers;

use App\Models\Offer;
use App\Models\Property;
use Carbon\Carbon;
use App\Support\Helper;
use App\Repositories\OfferRepository;
use App\Models\Tenancy;

class OfferObserver extends ParentObserver
{

    protected $offerRepository;
    
    public function __construct() {
      parent::__construct();
      $this->offerRepository = \App::make(OfferRepository::class);
    }
    
    public function deleted(Offer $model)
    {
        $model->messages()->delete();
        $model->documents()->delete();
        
        if (isset($model->previous_offer_id)) {
          $offer = isset($model->previousOffer) ? $model->previousOffer: $model->previousOffer();
          $offer->delete();
        }
        $tenancy = isset($model->tenancy) ? $model->tenancy: $model->tenancy();
        $tenancy->delete();
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
        foreach(isset($model->reviews) ? $model->reviews: $model->reviews() as $review) {
          $review->delete();
        }
        foreach(isset($model->transactions) ? $model->transactions: $model->transactions() as $transaction) {
          $transaction->delete();
        }
    }

    public function restored(Offer $model)
    {
        $model->messages()->restore();
        $model->documents()->restore();
        
        if (isset($model->previous_offer_id)) {
          $offer = isset($model->previousOffer) ? $model->previousOffer: $model->previousOffer();
          $offer->restore();
        }
        $tenancy = isset($model->tenancy) ? $model->tenancy: $model->tenancy();
        $tenancy->restore();
        
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
    
    public function creating(Offer $model)
    {
      if (isset($this->checkout)) {
        $contractDays = Carbon::parse($this->checkout)->diffInDays($this->checkin);
        if (!isset($model->type)) {
          if ($contractDays <= config('business.tenancy.max_short_duration_days')) {
            $model->type = Offer::TYPE_SHORT;
          } elseif ($contractDays <= config('business.tenancy.max_mid_duration_days')) {
            $model->type = Offer::TYPE_MID;
          } else if ($model->type != Offer::TYPE_RENEW) {
            $model->type = Offer::TYPE_LONG;
          }
        }
      }
      $model->updateSecurityDepositAmount();
      if (!isset($model->landlord_id)) {
        $model->landlord_id = $model->property->landlord_id;
      }
      $model->offer_expiry = Carbon::now()->addHours(config('business.tenancy.offer_accept_expiry'));
    }
    
    public function updated(Offer $model)
    {
      $original = $model->getOriginal();
      if ($original['status'] == Offer::REQUEST
          && $model->status == Offer::ACCEPT) {
        $this->offerAcceptNotification($model);
      }

      if ($original['status'] != $model->status
          && $model->status == Offer::REJECT) {
        $this->offerRejectedNotification($model);
      }
      // offer cancel
      if ($original['status'] != $model->status
          && $model->status == Offer::CANCEL) {
        $this->offerCancelNotification($model);
      }
        
      // initial deposit
      if ($original['status'] == Offer::ACCEPT
          && $model->status == Offer::INITIAL_DEPOSIT_MADE) {
        $this->offerInitialDepositNotificationAndUpdatePropertyAvailableDate($model);
      }

      //update property available date on cancel after deposit
      if ($original['status'] == Offer::INITIAL_DEPOSIT_MADE
          && $model->status == Offer::CANCEL) {
        $property = $model->property;
        $property->status = Property::STATUS_LIVE;
        $property->save();
      }
    }
    
    public function created(Offer $model)
    {
      $this->offerRequestNotification($model);
      
      $to = $this->offerRepository->getAllStalkHolderIds($model);
      $this->recordEvent('tenancy.offer_expiry', $model, $to, $model->toArray() + ['property_title' => $model->property->title], $model->offer_expiry);
      
      if (isset($model->previous_offer_id)) {
        $oldOffer = Offer::findorfail($model->previous_offer_id);
        $oldOffer->status = Offer::COUNTERED;
        $oldOffer->save();
      }
    }
    
    private function offerRequestNotification($model)
    {
      $to = $this->getMessageIntendors($model);
      if ($model->status == Offer::REQUEST) {
        $this->offerRepository->dispatchNotification('offer.offer_request', $model, $model->created_by, $to);
      } elseif ($model->status == Offer::COUNTER) {
        $this->offerRepository->dispatchNotification('offer.counter', $model, $model->created_by, $to);
      }
    }
    
    private function offerRejectedNotification($model)
    {
      $to = $this->getMessageIntendors($model);
      
      $this->offerRepository->dispatchNotification('offer.offer_reject', $model, $model->updated_by, $to);
    }
    
    private function offerInitialDepositNotificationAndUpdatePropertyAvailableDate($model)
    {
      $model->property->status = Property::STATUS_OCCUPIED;
      $model->property->save();
      
      list($type, $factor) = Helper::calculateFactorTime($model->holding_deposit_expiry, Carbon::now());
      $this->recordScore($model, $model->payout->user->id, config('business.scoring.default_type.responsiveness'), $type, $factor);
      
      $to = $this->offerRepository->getAllStalkHolderIds($model, [$model->tenant->id]);
      $this->offerRepository->dispatchNotification('offer.initial_deposit', $model, $model->tenant->id, $to);
      
      $to = $this->offerRepository->getAllStalkHolderIds($model);
      $this->recordEvent('tenancy.offer_holding_deposit_expiry', $model, $to, $model->toArray() + ['property_title' => $model->property->title], $model->holding_deposit_expiry);
    }
    
    private function offerAcceptNotification($model)
    {
      $from = null;
      if (isset($model->property_pro_id)) {
        $from = $model->property_pro_id;
      } else if (isset($model->landlord_id)) {
        $from = $model->landlord_id;
      }
      
      $this->offerRepository->dispatchNotification('offer.offer_accept', $model, $from, $model->tenant->id);
    }
    
    private function offerCancelNotification($model)
    {
      if ($model->updated_by == $model->tenant->id) {
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.offer'), 
          0, 1);
        $this->offerRepository->dispatchNotification('offer.cancel_tenant', $model, $model->tenant->id, $model->landlord->id);
      } elseif ($model->updated_by == $model->landlord->id) {
        $this->recordScore($model, $model->tenant->id, 
          config('business.scoring.default_type.offer'), 
          0, 1);
        $this->offerRepository->dispatchNotification('offer.cancel_landlord', $model, $model->landlord->id, $model->tenant->id);
      } else {
        // offerExpiredNotification
        $to = $this->offerRepository->getAllStalkHolderIds($model);
        $this->offerRepository->dispatchNotification('offer.cancel', $model, config('business.admin.id'), $to);
      }
    }
    
    private function getMessageIntendors($model)
    {
      $to = [];
      if ($model->updated_by == null) {
        if ($model->created_by == $model->landlord_id) {
          $to[] = $model->tenant_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        } else  if ($model->created_by == $model->tenant_id) {
          $to[] = $model->landlord_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        } else  if (isset($model->property_pro_id) && $model->created_by == $model->property_pro_id) {
          $to[] = $model->tenant_id;
          $to[] = $model->landlord_id;
        } else {
          $to[] = $model->tenant_id;
          $to[] = $model->landlord_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        }
      } else {
        if ($model->updated_by == $model->landlord_id) {
          $to[] = $model->tenant_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        } else  if ($model->updated_by == $model->tenant_id) {
          $to[] = $model->landlord_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        } else  if (isset($model->property_pro_id) && $model->updated_by == $model->property_pro_id) {
          $to[] = $model->tenant_id;
          $to[] = $model->landlord_id;
        } else {
          $to[] = $model->tenant_id;
          $to[] = $model->landlord_id;
          if (isset($model->property_pro_id)) $to[] = $model->property_pro_id;
        }
      }
      return $to;
    }
    
}