<?php 

namespace App\Observers;

use App\Models\Viewing;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ViewingRepository;

class ViewingObserver extends ParentObserver
{
    use DispatchesJobs;
    
    protected $viewingRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->viewingRepository = \App::make(ViewingRepository::class);
    }
    
    public function updated(Viewing $model)
    {
      $original = $model->getOriginal();
      if ($original['status'] != $model->status
          && $model->status == Viewing::CONFIRM) {
        $view_by_users = [];
        foreach ($model->viewingRequests as $confirmRequest) {
          $view_by_users[] = $confirmRequest->view_by_user;
          $this->recordEvent('viewing', $model, $confirmRequest->view_by_user, $model->toArray() + ['property_title' => $model->property->title], 
              $model->start_datetime);
        }
        $this->viewingRepository->dispatchNotification('viewing.confirm', $model, $model->conducted_by, $view_by_users);
        
        $this->recordEvent('viewing', $model, $model->conducted_by, $model->toArray() + ['property_title' => $model->property->title],
            $model->start_datetime);
      }
      
      if ($original['status'] != $model->status
          && $model->status == Viewing::DONE) {
        $this->viewingDone($model);
      }
      
      if ($original['checkin'] != $model->checkin) {
       $this->checkinViewing($model);
      }
    }
    
    public function deleted(Viewing $model)
    {
        
        $model->messages()->delete();
        
        foreach(isset($model->viewingRequestAll) ? $model->viewingRequestAll: $model->viewingRequestAll() as $viewingRequest) {
          $viewingRequest->delete();
        }
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
        foreach(isset($model->reviews) ? $model->reviews: $model->reviews() as $review) {
          $review->delete();
        }
    }
        
    public function restored(Viewing $model)
    {
        $model->messages()->restore();
        
        foreach(isset($model->viewingRequestAll) ? $model->viewingRequestAll: $model->viewingRequestAll() as $viewingRequest) {
          $viewingRequest->restore();
        }
        foreach(isset($model->reviews) ? $model->reviews: $model->reviews() as $review) {
          $review->restore();
        }
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->restore();
        }
    }
    
    private function viewingDone($model)
    {
      if (!$model->checkin) {
        $model->status = Viewing::NOSHOW_LANDLORD;
        $this->recordScore($model, $model->conducted_by, 
          config('business.scoring.default_type.viewing'), 
          0, 1);
      } else {
        $this->recordScore($model, $model->conducted_by, 
          config('business.scoring.default_type.viewing'), 
          1, 1);
        $this->viewingRepository->dispatchNotification('viewing.conduct_review', $model, config('business.admin.id'), $model->conducted_by, 1800);
      }
      
      $noshowConfirmCount = 0;
      foreach ($model->confirmRequests as $confirmRequest) {
        if (!$confirmRequest->checkin) {
          $noshowConfirmCount++;
          $this->recordScore($model, $confirmRequest->view_by_user, 
          config('business.scoring.default_type.viewing'), 
          0, 1);
        } else {
          $this->recordScore($model, $confirmRequest->view_by_user, 
          config('business.scoring.default_type.viewing'), 
          1, 1);
          $this->viewingRepository->dispatchNotification('viewing.conduct_review', $model, config('business.admin.id'), $confirmRequest->view_by_user, 1800);
        }
      }
      if ($noshowConfirmCount == count($model->confirmRequests)) {
        $model->status = Viewing::NOSHOW_TENANT;
      }
    }
    
    private function checkinViewing($model)
    {
      $to = [];
      foreach ($model->viewingRequests as $confirmRequest) {
        $to[] = $confirmRequest->view_by_user;
      }
      $this->viewingRepository->dispatchNotification('viewing.landlord_checkin', $model, $model->conducted_by, $to);
    }
}