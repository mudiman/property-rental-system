<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class RecordEvent implements ShouldQueue {

  use Dispatchable,
      InteractsWithQueue,
      Queueable,
      SerializesModels;

  private $title;
  private $description;
  private $to;
  private $start_datetime;
  private $end_datetime;
  private $eventable_id;
  private $eventable_type;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($config, $custom, $to, $start_datetime, $end_datetime, $eventable_id, $eventable_type, $description) {
    if ($config) {
      $custom = config("events.$config");
    } else {
      $custom = [];
      $custom['title'] = $config;
      $custom['description'] = $config;
    }
    $this->title = $custom['title'];
    $this->description = $description;

    $this->to = $to;
    $this->start_datetime = $start_datetime;
    $this->end_datetime = $end_datetime;
    $this->eventable_id = $eventable_id;
    $this->eventable_type = $eventable_type;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle() {
    $userIds = $this->to;
    if (!is_array($userIds)) {
      $userIds = [$this->to];
    }
    foreach ($userIds as $userId) {
      Event::updateOrCreate(
          [
            'title' => $this->title,
            'user_id' => $userId,
            'eventable_type' => $this->eventable_type,
            'eventable_id' => $this->eventable_id,
          ],
          [
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'description' => $this->description,
          ]
      );
    }
    Log::info('Registering Event');
  }

}
