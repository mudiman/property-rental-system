<?php 

namespace App\Observers;

use App\Models\Review;
use App\Repositories\ReviewRepository;

class ReviewObserver extends ParentObserver
{
    protected $reviewRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->reviewRepository = \App::make(ReviewRepository::class);
    }
    
    public function created(Review $model)
    {
      $this->reviewReceived($model);
    }
    
    public function deleted(Review $model)
    {
      $model->messages()->delete();
      foreach(isset($model->scoreable) ? $model->scoreable: $model->scoreable() as $score) {
        $score->delete();
      }
    }
        
    public function restored(Review $model)
    {
      $model->messages()->restore();
      foreach(isset($model->scoreable) ? $model->scoreable: $model->scoreable() as $score) {
        $score->restore();
      }
    }
    
    private function reviewReceived($model)
    {
      $score_type_id = $model->score_type_id;
      if (!$model->score_type_id) {
        switch ($model->reviewable_type) {
          case \App\Models\Offer::morphClass:
            $score_type_id = config('business.scoring.default_type.offer');
            break;
          case \App\Models\Tenancy::morphClass:
            $score_type_id = config('business.scoring.default_type.tenancy_bind');
            break;
          case \App\Models\Viewing::morphClass:
            $score_type_id = config('business.scoring.default_type.viewing_conduct');
            break;
          case \App\Models\PropertyPro::morphClass:
            $score_type_id = config('business.scoring.default_type.property_pro_conduct');
            break;
          case \App\Models\User::morphClass:
            $score_type_id = config('business.scoring.default_type.user');
            break;
        }
      }
      $factor = $model->score / config('business.scoring.review_max');
      $type = ($model->score > 0) ? 1: 0;
      $this->recordScore($model, $model->for_user, $score_type_id, $type, $factor);
      $this->reviewRepository->dispatchNotification('user.review', $model, $model->by_user, $model->for_user);
    }
}