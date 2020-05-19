<?php 

namespace App\Observers;

use App\Models\Property;
use App\Models\User;
use App\Repositories\PropertyRepository;

class PropertyObserver extends ParentObserver
{
    protected $propertyRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->propertyRepository = \App::make(PropertyRepository::class);
    }
    
    public function created(Property $model)
    {
        if (!$model->landlord->checkRole(User::ROLE_LANDLORD)) {
          $model->landlord->role = $model->landlord->role.",".User::ROLE_LANDLORD;
          $model->landlord->save();
        }
    }
    
    public function updated(Property $model)
    {
      //tracking history
      $original = $model->getOriginal();
      $this->recordHistory($model, $original);
    }
    
    public function deleted(Property $model)
    {
        $model->documents()->delete();
        $model->images()->delete();
        $model->messages()->delete();
        
        foreach($model->offers as $offer) {
          $offer->delete();
        }
        foreach($model->liked as $like) {
          $like->delete();
        }
        foreach($model->viewings as $viewing) {
          $viewing->delete();
        }
        foreach($model->reports as $report) {
          $report->delete();
        }
        foreach($model->statistics as $stat) {
          $stat->delete();
        }
        foreach($model->tenancies as $tenancy) {
          $tenancy->delete();
        }
        foreach($model->premiumlistings as $premiumlisting) {
          $premiumlisting->delete();
        }
        foreach($model->propertyProRequests as $propertyProRequest) {
          $propertyProRequest->delete();
        }
        foreach($model->reviews as $review) {
          $review->delete();
        }
    }
        
    public function restored(Property $model)
    {
        $model->documents()->restore();
        $model->images()->restore();
        $model->messages()->restore();
        
        foreach($model->offers as $offer) {
          $offer->restore();
        }
        foreach($model->liked as $like) {
          $like->restore();
        }
        foreach($model->viewings as $viewing) {
          $viewing->restore();
        }
        foreach($model->reports as $report) {
          $report->restore();
        }
        foreach($model->statistics as $stat) {
          $stat->restore();
        }
        foreach($model->tenancies as $tenancy) {
          $tenancy->restore();
        }
        foreach($model->premiumlistings as $premiumlisting) {
          $premiumlisting->restore();
        }
        foreach($model->propertyProRequests as $propertyProRequest) {
          $propertyProRequest->restore();
        }
        foreach($model->reviews as $review) {
          $review->restore();
        }
    }
}