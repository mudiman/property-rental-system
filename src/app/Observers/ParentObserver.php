<?php

namespace App\Observers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\RecordEvent;
use App\Jobs\SaveHistory;
use App\Jobs\RecordScore;
use App\Jobs\SendMessage;
use Illuminate\Support\Facades\View;

/**
 * Description of ParentObserver
 *
 * @author muda
 */
class ParentObserver
{
  use DispatchesJobs;
  
  public function __construct() 
  {
  }
  
  protected function recordScore($model, $userId, $scoreTypeId, $type, $factor)
  {
    $this->dispatch(
        (new RecordScore($userId, $model->id, $model::morphClass, $scoreTypeId, $type, $factor))
    );
  }
  
  protected function recordEvent($event, $model, $userId, $params, $startdatetime, $enddatetime = null)
  {
    
    $description = $this->makeView('reminders.'.config("events.$event.description"), $params);
    $this->dispatch(
        (new RecordEvent($event, null, $userId, $startdatetime, $enddatetime, $model->id, $model->morphClass, $description))
    );
  }
  
  protected function recordHistory($model, $original)
  {
    $this->dispatch(
        (new SaveHistory($model->id, $model->morphClass, json_encode($original)))
    );
  }
  
  protected function sendMessage($model)
  {
    $this->dispatch((new SendMessage($model->id))->onQueue(config('queue.message')));
  }
  
  private function makeView($templateName, $parameters)
  {
    if (View::exists($templateName)) {
        $view = View::make($templateName, $parameters);
        return $view->render();
    }
    return $templateName;
  }
}
