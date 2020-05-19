<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;

class UpComingReminder extends BaseCommand {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'reminder:upcoming';
  protected $remindDateTime;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Upcomming important event reminder';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();

    $this->remindDateStartTime = Carbon::now()->subMinutes(140);
    $this->remindDateEndTime = Carbon::now()->subMinutes(95);
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $this->logInfo("UpComingReminder");

    $events = Event::where('start_datetime', '>=', $this->remindDateStartTime->toDateTimeString())
        ->where('start_datetime', '<=', $this->remindDateEndTime->toDateTimeString())
        ->where('viewed', 0)
        ->with('eventable')->with('user')
        ->get();

    foreach ($events as $event) {
      try {
        (new \App\Support\Notification('user.reminder', [
          'fromUserId' => config('business.admin.id'),
          'toUserId' => $event->user_id,
          'first_name' => $event->user->first_name,
          'title' => $event->title,
          'description' => $event->description,
          'messageId' => $event->id,
          'messageType' => Event::morphClass,
          'eventable_id' => $event->eventable_id,
          'eventable_type' => $event->eventable_type,
          'eventable_date' => $event->start_datetime,
          'snapshot' => ''
        ]))->notify();
        $event->viewed = 1;
        $event->save();
      } catch (Exception $e) {
        $this->error('Error UpComingReminder ' . $e->getMessage());
      }
    }
  }
}
