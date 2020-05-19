<?php 

namespace App\Observers;
use App\Models\ViewingRequest;
use App\Models\Viewing;
use App\Repositories\ViewingRequestRepository;
use Carbon\Carbon;

class ViewingRequestObserver extends ParentObserver
{

    protected $viewRequestRepository;
    
    public function __construct() 
    {
      parent::__construct();
      $this->viewRequestRepository = \App::make(ViewingRequestRepository::class);
    }
    
    public function deleted(ViewingRequest $model)
    {
        $model->messages()->delete();
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->delete();
        }
    }

    public function restored(ViewingRequest $model)
    {
        $model->messages()->restore();
        
        foreach(isset($model->events) ? $model->events: $model->events() as $event) {
          $event->restore();
        }
    }

    public function updated(ViewingRequest $model)
    {
      $original = $model->getOriginal();

      if ($original['viewing_id'] != $model->viewing_id) {
        $this->makeOldViewingAvailableIfLastConfirmRequest($original['viewing_id'], $model);
        $this->rearrangeViewingRequest($model);
      }
      if ($original['status'] != $model->status
          && $model->status == ViewingRequest::STATUS_CONFIRM) {
        $this->tenantConfirmViewing($model);
      }
      if ($original['status'] != $model->status
          && $model->status == ViewingRequest::STATUS_CANCEL) {
        $this->viewingRequestCancel($model);
      }
      
      if ($original['checkin'] != $model->checkin) {
       $this->checkinViewingRequest($model);
      }
    }


    public function created(ViewingRequest $model)
    {
      $this->viewRequestRepository->dispatchNotification('viewing.request', $model, $model->view_by_user, $model->viewing->conducted_by);
    }
    
    private function rearrangeViewingRequest($model)
    {
      $to = $this->getMessageIntendors($model);
      $this->viewRequestRepository->dispatchNotification('viewing.rearrange', $model, $model->updated_by, $to);
    }
    
    private function tenantConfirmViewing($model)
    {
      $to = $this->getMessageIntendors($model);
      $this->viewRequestRepository->dispatchNotification('viewing.rearrange_confirm', $model, $model->updated_by, $to);
    }
    
    private function makeOldViewingAvailableIfLastConfirmRequest($originalViewingId, $model)
    {
      $originalViewing = Viewing::where('id', $originalViewingId)->where('status', Viewing::CONFIRM)->with('confirmOrRearrangeConfirmRequests')->first();
      if (!empty($originalViewing) && count($originalViewing->confirmOrRearrangeConfirmRequests) == 0) {  
        $originalViewing->status = Viewing::AVAILABLE;
        $originalViewing->save();
      }
    }
    
    private function viewingRequestCancel($model)
    {
      $to = $this->getMessageIntendors($model);
      $from = isset($model->updated_by) ? $model->updated_by: config('business.admin.id');
      $this->viewRequestRepository->dispatchNotification('viewing.cancel', $model, $from, $to);
    }
    
    private function checkinViewingRequest($model)
    {
      $this->viewRequestRepository->dispatchNotification('viewing.tenant_checkin', $model, $model->updated_by, $model->viewing->conducted_by);
    }
    
    private function getMessageIntendors($model)
    {
      $to = [];
      if ($model->updated_by == null) {
        $to[] = $model->view_by_user;
        $to[] = $model->viewing->conducted_by;
      } elseif ($model->updated_by == $model->viewing->conducted_by) {
        $to[] = $model->view_by_user;
      } else {
        $to[] = $model->viewing->conducted_by;
      }
      return $to;
    }
}