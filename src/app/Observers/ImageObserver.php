<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Image;
use App\Models\Property;
use App\Repositories\ImageRepository;

class ImageObserver extends ParentObserver {

  protected $imageRepository;

  public function __construct() {
    parent::__construct();
    $this->imageRepository = \App::make(ImageRepository::class);
  }
  
  public function deleting(Image $model) {
    $image = Image::with('imageable')
        ->where('imageable_id', $model->imageable_id)->where('imageable_type', $model->imageable_type)
        ->orderBy('updated_at', 'desc')
        ->first();
    if (!empty($image)) {
      $imageable = $image->imageable;
      $imageable->profile_picture = $image->path;
      $imageable->save();
    } else {
      $imageable = $model->imageable;
      $imageable->profile_picture = null;
      $imageable->save();
    }
  }

  public function saved(Image $model) {
    $this->updateUserPropertyProfilePicture($model);
  }
  
  private function updateUserPropertyProfilePicture(Image $model) {
    switch ($model->imageable_type) {
      case User::morphClass:
        $model->imageable->profile_picture = $model->path;
        $model->imageable->save();
        break;
      case Property::morphClass:
        if ($model->primary == true 
            || empty($model->imageable->profile_picture) 
            || !Image::where('imageable_id', $model->imageable_id)->where('imageable_type', $model->imageable_type)->exists()) {
          $model->imageable->profile_picture = $model->path;
          $model->imageable->save();
        }
        break;
    }
  }

}
