<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Like;
use App\Models\Property;
use App\Repositories\LikeRepository;

class LikeObserver extends ParentObserver
{

  protected $likeRepository;

  public function __construct() {
    parent::__construct();
    $this->likeRepository = \App::make(LikeRepository::class);
  }

  public function created(Like $model) {
    $this->likeNotification('user.like', $model);
  }

  public function delete(Like $model) {
    $this->likeNotification('user.unlike', $model);
  }

  private function likeNotification($config, $model) {
    $to = null;
    switch ($model->likeable_type) {
      case User::morphClass:
        $to = $model->likeable->id;
        break;
      case Property::morphClass:
        $to = $model->likeable->landlord_id;
        break;
    }
    $this->likeRepository->dispatchNotification($config, $model, $model->user_id, $to);
  }

}
