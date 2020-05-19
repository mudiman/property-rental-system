<?php 

namespace App\Observers;

use App\Models\Score;
use App\Repositories\ScoreRepository;

class ScoreObserver extends ParentObserver 
{
    protected $scoringRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->scoringRepository = \App::make(ScoreRepository::class);
    }
    
    public function deleted(Score $model)
    {
      $model->messages()->delete();
    }
        
    public function restored(Score $model)
    {
      $model->messages()->restore();
    }
    
    public function updated(Score $model)
    {
      $from = !empty($model->updated_by) ? $model->updated_by: config('business.admin.id');
      $this->scoringRepository->dispatchNotification('user.scoring', $model, $from, $model->user_id);
    }
    
    public function created(Score $model)
    {
        $from = !empty($model->created_by) ? $model->created_by: config('business.admin.id');
        $this->scoringRepository->dispatchNotification('user.scoring', $model, $from, $model->user_id);
    }
}