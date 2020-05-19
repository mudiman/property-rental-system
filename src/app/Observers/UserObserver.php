<?php 

namespace App\Observers;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class UserObserver extends ParentObserver
{

    protected $userRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->userRepository = \App::make(UserRepository::class);
    }
    
    
    public function creating(User $model)
    {
      if (!isset($model->username)) {
        $model->username = $model->email;
      }
      $model->email_verification_code_expiry = Carbon::now()->addDay(config('business.registration.expiry'));
      $model->email_verification_code = str_random(10);
      $model->sms_verification_code_expiry = Carbon::now()->addDay(config('business.registration.expiry'));
      $model->sms_verification_code = str_random(5);
      
      $model->available_to_move_on = Carbon::now();
    }
    
    public function created(User $model)
    {
      $this->userRepository->sendRegistrationVerificationLink($model);
    }
    
    public function updated(User $model)
    {
      $original = $model->getOriginal();
      if ($original['admin_verified'] == 0
          && $model->admin_verified == 1) {
        $this->userRepository->dispatchNotification('user.verified', $model, config("business.admin.id"), $model->id);
      }
    }
    
    public function deleted(User $model)
    {
        $model->documents()->delete();
        $model->images()->delete();
        $model->myMessages()->delete();
        $model->messages()->delete();
        $model->participants()->delete();
        $model->payins()->delete();
        $model->payouts()->delete();
        $model->events()->delete();
        $model->agent()->delete();
        
        foreach($model->statistics as $stat) {
          $stat->delete();
        }
        foreach($model->viewingRequests as $viewingRequest) {
          $viewingRequest->delete();
        }
        foreach($model->viewings as $viewing) {
          $viewing->delete();
        }
        foreach($model->likes as $like) {
          $like->delete();
        }
        foreach($model->liked as $like) {
          $like->delete();
        }
        foreach($model->myProperties as $property) {
          $property->delete();
        }
        foreach($model->agencies as $agency) {
          $agency->delete();
        }
        foreach($model->myReferences as $myReference) {
          $myReference->delete();
        }
        foreach($model->tenanciesAsLandlord as $tenancy) {
          $tenancy->delete();
        }
        foreach($model->tenanciesAsTenant as $tenancy) {
          $tenancy->delete();
        }
        foreach($model->reviewsReceived as $reviewsReceived) {
          $reviewsReceived->delete();
        }
        foreach($model->myReviews as $review) {
          $review->delete();
        }
        foreach($model->reportedBy as $report) {
          $report->delete();
        }
        foreach($model->userServices as $service) {
          $service->delete();
        }
    }
        
    public function restored(User $model)
    {
        $model->documents()->restore();
        $model->images()->restore();
        $model->myMessages()->restore();
        $model->messages()->restore();
        $model->participants()->restore();
        $model->payins()->restore();
        $model->payouts()->restore();
        $model->events()->restore();
        $model->viewingRequests()->restore();
        $model->agent()->restore();
        
        foreach($model->statistics as $stat) {
          $stat->restore();
        }
        foreach($model->viewingRequests as $viewingRequest) {
          $viewingRequest->restore();
        }
        foreach($model->viewings as $viewing) {
          $viewing->restore();
        }
        foreach($model->likes as $like) {
          $like->restore();
        }
        foreach($model->liked as $like) {
          $like->restore();
        }
        foreach($model->myProperties as $property) {
          $property->restore();
        }
        foreach($model->agencies as $agency) {
          $agency->restore();
        }
        foreach($model->myReferences as $myReference) {
          $myReference->restore();
        }
        foreach($model->tenanciesAsLandlord as $tenancy) {
          $tenancy->restore();
        }
        foreach($model->tenanciesAsTenant as $tenancy) {
          $tenancy->restore();
        }
        foreach($model->reviewsReceived as $reviewsReceived) {
          $reviewsReceived->restore();
        }
        foreach($model->myReviews as $review) {
          $review->restore();
        }
        foreach($model->reportedBy as $report) {
          $report->restore();
        }
        foreach($model->userServices as $service) {
          $service->restore();
        }
    }
}