<?php 

namespace App\Observers;
use App\Models\Thread;
use App\Repositories\ThreadRepository;

class ThreadObserver extends ParentObserver
{

    protected $viewingRepository;
    
    public function __construct()
    {
      parent::__construct();
      $this->threadRepository = \App::make(ThreadRepository::class);
    }
    
    
    public function deleted(Thread $model)
    {
        $model->messages()->delete();
        $model->participants()->delete();
    }
        
    public function restored(Thread $model)
    {
        $model->messages()->restore();
        $model->participants()->restore();
    }
}