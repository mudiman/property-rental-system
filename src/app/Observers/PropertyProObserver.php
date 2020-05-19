<?php 

namespace App\Observers;
use App\Models\PropertyPro;
use App\Repositories\PropertyProRepository;

class PropertyProObserver extends ParentObserver
{

    protected $propertyProRepository;
    
    public function __construct() {
      parent::__construct();
      $this->propertyProRepository = \App::make(PropertyProRepository::class);
    }
    
    public function updated(PropertyPro $model)
    {
      $original = $model->getOriginal();
      if ($original['status'] == PropertyPro::REQUEST
          && $model->status == PropertyPro::ACCEPT) {
        $this->propertyProAcceptNotification($model);
      }

      if ($original['status'] != $model->status
          && $model->status == PropertyPro::REJECT) {
        $this->propertyProRejectedNotification($model);
      }
    }
    
    public function created(PropertyPro $model)
    {
      $this->propertyProRequestNotification($model);
    }
    
    private function propertyProAcceptNotification($model)
    {
      $this->propertyProRepository->dispatchNotification('propertyPro.accept', $model, $model->landlord_id, $model->property_pro_id);
    }
    
    private function propertyProRequestNotification($model)
    {
      $this->propertyProRepository->dispatchNotification('propertyPro.request', $model, $model->property_pro_id, $model->landlord_id);
    }
    
    private function propertyProRejectedNotification($model)
    {
      $this->propertyProRepository->dispatchNotification('propertyPro.reject', $model, $model->landlord_id, $model->property_pro_id);
    }
}